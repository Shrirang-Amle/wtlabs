<?php
$authorities = [
    'plastic' => [
        'name' => 'Plastic Recycling Unit',
        'action' => 'Segregate the plastic waste, send it for recycling, and monitor repeated dumping in the area.',
        'eta' => 'within 12 hours',
    ],
    'paper' => [
        'name' => 'Paper Recovery Team',
        'action' => 'Collect the paper waste, keep it dry, and transfer it to the paper recycling center.',
        'eta' => 'within 10 hours',
    ],
    'metal' => [
        'name' => 'Scrap Handling Department',
        'action' => 'Collect the metal waste safely and move it to the municipal scrap segregation yard.',
        'eta' => 'within 18 hours',
    ],
    'glass' => [
        'name' => 'Glass Disposal Squad',
        'action' => 'Use protective handling, collect broken glass separately, and deliver it for glass processing.',
        'eta' => 'within 8 hours',
    ],
    'mixed' => [
        'name' => 'Municipal Sanitation Team',
        'action' => 'Inspect the mixed waste, separate recyclable items, and dispose of non-recyclable material responsibly.',
        'eta' => 'within 24 hours',
    ],
];

$errors = [];
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = trim($_POST['location'] ?? '');
    $wasteType = $_POST['waste_type'] ?? '';
    $details = trim($_POST['details'] ?? '');
    $reporter = trim($_POST['reporter'] ?? '');

    if ($location === '') {
        $errors[] = 'Location is required.';
    }

    if (!array_key_exists($wasteType, $authorities)) {
        $errors[] = 'Please select a valid waste type.';
    }

    if ($details === '') {
        $errors[] = 'Please provide a short description of the waste.';
    }

    if ($reporter === '') {
        $errors[] = 'Reporter name is required.';
    }

    if (!$errors) {
        $assignment = $authorities[$wasteType];
        $ticketNumber = 'WMS-' . date('Ymd') . '-' . strtoupper(substr(md5($location . $wasteType . microtime()), 0, 6));

        $result = [
            'ticket' => $ticketNumber,
            'location' => htmlspecialchars($location, ENT_QUOTES, 'UTF-8'),
            'wasteType' => ucfirst($wasteType),
            'details' => htmlspecialchars($details, ENT_QUOTES, 'UTF-8'),
            'reporter' => htmlspecialchars($reporter, ENT_QUOTES, 'UTF-8'),
            'authority' => $assignment['name'],
            'action' => $assignment['action'],
            'eta' => $assignment['eta'],
            'reportedAt' => date('d-m-Y h:i A'),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Management System</title>
    <style>
        :root {
            --bg: #eef7ef;
            --card: #ffffff;
            --primary: #146c43;
            --primary-dark: #0d4e30;
            --accent: #f4b942;
            --text: #1e2a24;
            --muted: #647067;
            --danger: #c0392b;
            --border: #d7e4d8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e8f5e9, #fefae0);
            color: var(--text);
        }

        .wrapper {
            max-width: 960px;
            margin: 40px auto;
            padding: 20px;
        }

        .hero,
        .panel {
            background: var(--card);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 14px 32px rgba(20, 108, 67, 0.12);
            border: 1px solid var(--border);
        }

        .hero {
            margin-bottom: 24px;
        }

        h1,
        h2 {
            margin-top: 0;
        }

        .hero p,
        .note {
            color: var(--muted);
            line-height: 1.6;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #b9cdbd;
            font-size: 15px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .btn {
            border: none;
            background: var(--primary);
            color: #fff;
            padding: 13px 22px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .errors {
            margin-bottom: 18px;
            padding: 14px 18px;
            background: #fdecea;
            color: var(--danger);
            border-radius: 10px;
            border: 1px solid #f5c6cb;
        }

        .result {
            margin-top: 24px;
            background: #f7fff8;
            border-left: 6px solid var(--primary);
            padding: 22px;
            border-radius: 14px;
        }

        .result strong {
            color: var(--primary-dark);
        }

        .badge {
            display: inline-block;
            background: #e1f3e7;
            color: var(--primary-dark);
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 14px;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <section class="hero">
            <h1>Waste Collection Management System</h1>
            <p>
                This system accepts the location of waste such as plastic, paper, metal, glass, or mixed garbage.
                After submission, it directs the concerned authority to collect and manage the waste properly.
            </p>
        </section>

        <section class="panel">
            <h2>Report Waste</h2>
            <p class="note">Fill in the details below to create a waste collection request.</p>

            <?php if ($errors): ?>
                <div class="errors">
                    <strong>Please fix the following:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="grid">
                    <div>
                        <label for="reporter">Reporter Name</label>
                        <input type="text" id="reporter" name="reporter" value="<?php echo htmlspecialchars($_POST['reporter'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Enter your name">
                    </div>

                    <div>
                        <label for="waste_type">Waste Type</label>
                        <select id="waste_type" name="waste_type">
                            <option value="">Select waste type</option>
                            <?php foreach ($authorities as $type => $info): ?>
                                <option value="<?php echo $type; ?>" <?php echo (($_POST['waste_type'] ?? '') === $type) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="full">
                        <label for="location">Waste Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Example: Near Bus Stand, MG Road, Pune">
                    </div>

                    <div class="full">
                        <label for="details">Description</label>
                        <textarea id="details" name="details" placeholder="Describe the quantity or condition of the waste"><?php echo htmlspecialchars($_POST['details'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="full">
                        <button type="submit" class="btn">Submit Waste Collection Request</button>
                    </div>
                </div>
            </form>

            <?php if ($result): ?>
                <div class="result">
                    <div class="badge">Request Submitted Successfully</div>
                    <p><strong>Ticket Number:</strong> <?php echo $result['ticket']; ?></p>
                    <p><strong>Reported By:</strong> <?php echo $result['reporter']; ?></p>
                    <p><strong>Location:</strong> <?php echo $result['location']; ?></p>
                    <p><strong>Waste Type:</strong> <?php echo $result['wasteType']; ?></p>
                    <p><strong>Description:</strong> <?php echo $result['details']; ?></p>
                    <p><strong>Concerned Authority:</strong> <?php echo $result['authority']; ?></p>
                    <p><strong>Management Action:</strong> <?php echo $result['action']; ?></p>
                    <p><strong>Expected Collection Time:</strong> <?php echo $result['eta']; ?></p>
                    <p><strong>Reported At:</strong> <?php echo $result['reportedAt']; ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
