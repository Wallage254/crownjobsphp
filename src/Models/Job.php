<?php

class Job {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM jobs WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['location'])) {
            $sql .= " AND location ILIKE ?";
            $params[] = '%' . $filters['location'] . '%';
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (title ILIKE ? OR description ILIKE ? OR company ILIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['salaryMin'])) {
            $sql .= " AND salary_min >= ?";
            $params[] = $filters['salaryMin'];
        }

        if (!empty($filters['salaryMax'])) {
            $sql .= " AND salary_max <= ?";
            $params[] = $filters['salaryMax'];
        }

        $sql .= " ORDER BY created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getById($id) {
        $sql = "SELECT * FROM jobs WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    public function create($data) {
        $sql = "INSERT INTO jobs (id, title, company, category, location, description, requirements, salary_min, salary_max, job_type, is_urgent, visa_sponsored, company_logo, workplace_images, created_at, updated_at) 
                VALUES (gen_random_uuid(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) 
                RETURNING *";
        
        $workplaceImages = !empty($data['workplace_images']) ? json_encode($data['workplace_images']) : null;
        
        $params = [
            $data['title'],
            $data['company'],
            $data['category'],
            $data['location'],
            $data['description'],
            $data['requirements'],
            $data['salary_min'] ?? null,
            $data['salary_max'] ?? null,
            $data['job_type'] ?? 'Full-time',
            $data['is_urgent'] ?? false,
            $data['visa_sponsored'] ?? true,
            $data['company_logo'] ?? null,
            $workplaceImages
        ];

        return $this->db->fetchOne($sql, $params);
    }

    public function update($id, $data) {
        $setParts = [];
        $params = [];

        foreach (['title', 'company', 'category', 'location', 'description', 'requirements', 'salary_min', 'salary_max', 'job_type', 'is_urgent', 'visa_sponsored', 'company_logo'] as $field) {
            if (array_key_exists($field, $data)) {
                $setParts[] = "$field = ?";
                $params[] = $data[$field];
            }
        }

        if (array_key_exists('workplace_images', $data)) {
            $setParts[] = "workplace_images = ?";
            $params[] = json_encode($data['workplace_images']);
        }

        if (empty($setParts)) {
            return false;
        }

        $setParts[] = "updated_at = NOW()";
        $params[] = $id;

        $sql = "UPDATE jobs SET " . implode(', ', $setParts) . " WHERE id = ? RETURNING *";
        return $this->db->fetchOne($sql, $params);
    }

    public function delete($id) {
        $sql = "DELETE FROM jobs WHERE id = ?";
        return $this->db->execute($sql, [$id]) > 0;
    }
}