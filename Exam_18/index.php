<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/request_handler.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main-box">
        <div class="section">
            <h1>Complaint Management System</h1>
            <p>Submit and track complaints related to services from organizations such as PMC, PMT, or any other institution.</p>
        </div>

        <div class="section">
            <h2>Register Complaint</h2>

            <?php if ($message !== ''): ?>
                <div class="message"><?= clean($message) ?></div>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
                <div class="error-box">
                    <strong>Please fix the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= clean($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="action" value="add">

                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= clean($_POST['name'] ?? '') ?>">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= clean($_POST['email'] ?? '') ?>">

                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?= clean($_POST['phone'] ?? '') ?>">

                <label for="organization">Organization</label>
                <select id="organization" name="organization">
                    <option value="">Select organization</option>
                    <?php foreach ($organizations as $organization): ?>
                        <option value="<?= clean($organization) ?>" <?= (($_POST['organization'] ?? '') === $organization) ? 'selected' : '' ?>>
                            <?= clean($organization) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="service">Service Type</label>
                <input type="text" id="service" name="service" placeholder="Example: Bus Pass, Road Repair, Water Connection" value="<?= clean($_POST['service'] ?? '') ?>">

                <label for="subject">Complaint Subject</label>
                <input type="text" id="subject" name="subject" value="<?= clean($_POST['subject'] ?? '') ?>">

                <label for="description">Complaint Description</label>
                <textarea id="description" name="description"><?= clean($_POST['description'] ?? '') ?></textarea>

                <button type="submit">Submit Complaint</button>
            </form>
        </div>

        <div class="section">
            <h2>Complaint Records</h2>

            <form method="get">
                <label for="filter_organization">Filter by Organization</label>
                <select id="filter_organization" name="organization">
                    <option value="">All organizations</option>
                    <?php foreach ($organizations as $organization): ?>
                        <option value="<?= clean($organization) ?>" <?= $organizationFilter === $organization ? 'selected' : '' ?>>
                            <?= clean($organization) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="filter_status">Filter by Status</label>
                <select id="filter_status" name="status">
                    <option value="">All statuses</option>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= clean($status) ?>" <?= $statusFilter === $status ? 'selected' : '' ?>>
                            <?= clean($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Apply Filter</button>
            </form>

            <?php if ($filteredComplaints === []): ?>
                <div class="empty">No complaints found.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name and Organization</th>
                            <th>Complaint Details</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($filteredComplaints) as $complaint): ?>
                            <tr>
                                <td data-label="ID">#<?= (int) $complaint['id'] ?></td>
                                <td data-label="Name and Organization">
                                    <?= clean($complaint['name']) ?><br>
                                    <?= clean($complaint['email']) ?><br>
                                    <?= clean($complaint['phone']) ?><br>
                                    <?= clean($complaint['organization']) ?>
                                </td>
                                <td data-label="Complaint Details">
                                    <strong><?= clean($complaint['subject']) ?></strong><br>
                                    Service: <?= clean($complaint['service']) ?><br>
                                    <?= nl2br(clean($complaint['description'])) ?><br>
                                    Date: <?= clean($complaint['created_at']) ?>
                                </td>
                                <td data-label="Status"><?= clean($complaint['status']) ?></td>
                                <td data-label="Update Status">
                                    <form method="post" class="small-form">
                                        <input type="hidden" name="action" value="update_status">
                                        <input type="hidden" name="complaint_id" value="<?= (int) $complaint['id'] ?>">
                                        <select name="status">
                                            <?php foreach ($statuses as $status): ?>
                                                <option value="<?= clean($status) ?>" <?= $complaint['status'] === $status ? 'selected' : '' ?>>
                                                    <?= clean($status) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit">Save</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
