<?php
declare(strict_types=1);

function loadComplaints(string $filePath): array
{
    $raw = file_get_contents($filePath);

    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $complaints = json_decode($raw, true);
    return is_array($complaints) ? $complaints : [];
}

function saveComplaints(string $filePath, array $complaints): void
{
    file_put_contents($filePath, json_encode($complaints, JSON_PRETTY_PRINT));
}

function nextComplaintId(array $complaints): int
{
    if ($complaints === []) {
        return 1;
    }

    $ids = array_column($complaints, 'id');
    return max($ids) + 1;
}

function clean(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function validateComplaint(array $input): array
{
    $errors = [];

    if (trim($input['name'] ?? '') === '') {
        $errors[] = 'Name is required.';
    }

    $email = trim($input['email'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email address is required.';
    }

    if (trim($input['organization'] ?? '') === '') {
        $errors[] = 'Organization is required.';
    }

    if (trim($input['service'] ?? '') === '') {
        $errors[] = 'Service is required.';
    }

    if (trim($input['subject'] ?? '') === '') {
        $errors[] = 'Complaint subject is required.';
    }

    if (trim($input['description'] ?? '') === '') {
        $errors[] = 'Complaint description is required.';
    }

    return $errors;
}

function createComplaint(array $input, array $complaints): array
{
    return [
        'id' => nextComplaintId($complaints),
        'name' => trim($input['name'] ?? ''),
        'email' => trim($input['email'] ?? ''),
        'phone' => trim($input['phone'] ?? ''),
        'organization' => trim($input['organization'] ?? ''),
        'service' => trim($input['service'] ?? ''),
        'subject' => trim($input['subject'] ?? ''),
        'description' => trim($input['description'] ?? ''),
        'status' => 'Pending',
        'created_at' => date('Y-m-d H:i:s'),
    ];
}

function updateComplaintStatus(array $complaints, int $complaintId, string $status): array
{
    foreach ($complaints as &$complaint) {
        if ((int) $complaint['id'] === $complaintId) {
            $complaint['status'] = $status;
            break;
        }
    }
    unset($complaint);

    return $complaints;
}

function filterComplaints(array $complaints, string $organizationFilter, string $statusFilter): array
{
    return array_values(array_filter(
        $complaints,
        static function (array $complaint) use ($organizationFilter, $statusFilter): bool {
            $organizationMatches = $organizationFilter === ''
                || strcasecmp($complaint['organization'], $organizationFilter) === 0;
            $statusMatches = $statusFilter === ''
                || strcasecmp($complaint['status'], $statusFilter) === 0;

            return $organizationMatches && $statusMatches;
        }
    ));
}
