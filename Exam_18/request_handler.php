<?php
declare(strict_types=1);

$message = '';
$errors = [];
$complaints = loadComplaints($dataFile);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $errors = validateComplaint($_POST);

        if ($errors === []) {
            $complaints[] = createComplaint($_POST, $complaints);
            saveComplaints($dataFile, $complaints);

            header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
            exit;
        }
    }

    if ($action === 'update_status') {
        $complaintId = (int) ($_POST['complaint_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if (in_array($status, $statuses, true)) {
            $complaints = updateComplaintStatus($complaints, $complaintId, $status);
            saveComplaints($dataFile, $complaints);

            header('Location: ' . $_SERVER['PHP_SELF'] . '?updated=1');
            exit;
        }
    }
}

if (isset($_GET['success'])) {
    $message = 'Complaint submitted successfully.';
}

if (isset($_GET['updated'])) {
    $message = 'Complaint status updated successfully.';
}

$organizationFilter = trim($_GET['organization'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$filteredComplaints = filterComplaints($complaints, $organizationFilter, $statusFilter);
