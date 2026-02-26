<?php
session_start();
include('db_conn.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Stats Counting
$total_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues");
$pending_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE status='Pending'");
$process_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE status='In Progress'");
$resolved_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE status='Resolved'");

$total = mysqli_fetch_assoc($total_q)['t'] ?? 0;
$pending = mysqli_fetch_assoc($pending_q)['t'] ?? 0;
$process = mysqli_fetch_assoc($process_q)['t'] ?? 0;
$resolved = mysqli_fetch_assoc($resolved_q)['t'] ?? 0;

$query = "SELECT issues.*, users.username FROM issues 
          JOIN users ON issues.user_id = users.user_id 
          ORDER BY issues.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetIssue Elite | Admin Terminal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root { --primary-gradient: linear-gradient(135deg, #0f172a 0%, #334155 100%); }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
        .navbar { background: var(--primary-gradient) !important; border-bottom: 3px solid #3b82f6; }
        
        .status-dot { width: 10px; height: 10px; background: #10b981; border-radius: 50%; display: inline-block; margin-right: 8px; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }

        .stat-card { border: none; border-radius: 24px; background: #ffffff; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        
        .icon-shape { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; }
        .search-bar { border-radius: 12px; border: 1px solid #e2e8f0; padding-left: 40px; transition: 0.3s; }
        .search-bar:focus { box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); border-color: #3b82f6; }
        
        .modal { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(4px); }
        .modal-backdrop { display: none !important; }
        .modal-content { border-radius: 28px; border: none; overflow: hidden; }
        .description-box { background: #f1f5f9; border-radius: 12px; padding: 15px; border-left: 4px solid #3b82f6; color: #475569; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-lg py-3 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="#">
            <i class="bi bi-shield-check-fill me-2 text-primary"></i> NETISSUE <span class="ms-2 badge bg-primary bg-opacity-10 text-primary small">v2.0 Elite</span>
        </a>
        <div class="d-flex align-items-center bg-dark bg-opacity-25 rounded-pill px-3 py-1">
            <span class="status-dot"></span>
            <span class="text-white small fw-medium">SYSTEM ONLINE: <span id="current-time"></span></span>
        </div>
        <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">Sign Out</a>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-3" data-aos="fade-up">
            <div class="card stat-card p-4 border-0">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-secondary small fw-bold mb-1">ALL REPORTS</p>
                        <h2 class="fw-black mb-0"><?php echo $total; ?></h2>
                    </div>
                    <div class="icon-shape bg-primary bg-opacity-10 text-primary"><i class="bi bi-database-fill fs-3"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card stat-card p-4 border-0 text-warning">
                <div class="d-flex justify-content-between">
                    <div><p class="small fw-bold mb-1">PENDING</p><h2 class="fw-black mb-0"><?php echo $pending; ?></h2></div>
                    <div class="icon-shape bg-warning bg-opacity-10 text-warning"><i class="bi bi-exclamation-triangle-fill fs-3"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card stat-card p-4 border-0 text-info">
                <div class="d-flex justify-content-between">
                    <div><p class="small fw-bold mb-1">PROCESSING</p><h2 class="fw-black mb-0"><?php echo $process; ?></h2></div>
                    <div class="icon-shape bg-info bg-opacity-10 text-info"><i class="bi bi-activity fs-3"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card stat-card p-4 border-0 text-success">
                <div class="d-flex justify-content-between">
                    <div><p class="small fw-bold mb-1">RESOLVED</p><h2 class="fw-black mb-0"><?php echo $resolved; ?></h2></div>
                    <div class="icon-shape bg-success bg-opacity-10 text-success"><i class="bi bi-patch-check-fill fs-3"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card stat-card shadow-sm p-4 h-100 border-0">
                <h6 class="fw-bold mb-4 text-center">ISSUE DISTRIBUTION</h6>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card stat-card shadow-sm p-4 border-0 h-100">
                <div class="d-md-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Incident Logs</h5>
                    <div class="d-flex gap-2">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" id="logSearch" class="form-control search-bar ps-5" placeholder="Search...">
                        </div>
                        <button onclick="window.location.reload();" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <a href="delete_issue.php?all=true" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('AMARAN: Padam semua history?')">
                            <i class="bi bi-trash3-fill"></i>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="logTable">
                        <thead class="table-light">
                            <tr class="small text-secondary">
                                <th>REPORTER</th>
                                <th>CATEGORY</th>
                                <th>STATUS</th>
                                <th class="text-end">MANAGEMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): 
                                $s = $row['status'];
                                $b = ($s == 'Pending') ? 'bg-warning' : (($s == 'In Progress') ? 'bg-info text-white' : 'bg-success text-white');
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['username']); ?></div>
                                    <small class="text-muted">#<?php echo $row['issue_id']; ?></small>
                                </td>
                                <td><span class="text-muted small fw-bold text-uppercase"><?php echo $row['category']; ?></span></td>
                                <td><span class="badge <?php echo $b; ?> rounded-pill px-3 py-2"><?php echo $s; ?></span></td>
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $row['issue_id']; ?>">
                                        Manage
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="updateModal<?php echo $row['issue_id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-2xl">
                                        <div class="bg-primary py-2"></div>
                                        <form action="update_status.php" method="POST">
                                            <div class="modal-body p-5">
                                                <div class="text-center mb-4">
                                                    <h4 class="fw-bold">Manage Ticket #<?php echo $row['issue_id']; ?></h4>
                                                    <p class="text-muted small">Submitted by <strong><?php echo htmlspecialchars($row['username']); ?></strong></p>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label class="form-label small fw-bold text-uppercase text-primary">User Description</label>
                                                    <div class="description-box">
                                                        <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="issue_id" value="<?php echo $row['issue_id']; ?>">
                                                
                                                <div class="mb-4">
                                                    <label class="form-label small fw-bold text-uppercase">Update Status</label>
                                                    <select name="status" class="form-select border-0 bg-light py-3 rounded-3 shadow-none">
                                                        <option value="Pending" <?php if($s == 'Pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="In Progress" <?php if($s == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                                        <option value="Resolved" <?php if($s == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label small fw-bold text-uppercase">Admin Solution Remarks</label>
                                                    <textarea name="remarks" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" rows="3" placeholder="Write the fix details here..."><?php echo htmlspecialchars($row['admin_remarks']); ?></textarea>
                                                </div>

                                                <button type="submit" name="update_now" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">Save & Update</button>
                                                <button type="button" class="btn btn-link w-100 mt-2 text-muted text-decoration-none small" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    AOS.init();
    function updateClock() {
        const now = new Date();
        document.getElementById('current-time').innerText = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000); updateClock();

    document.getElementById('logSearch').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#logTable tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Resolved'],
            datasets: [{
                data: [<?php echo $pending; ?>, <?php echo $process; ?>, <?php echo $resolved; ?>],
                backgroundColor: ['#f59e0b', '#0ea5e9', '#10b981'],
                borderWidth: 0
            }]
        },
        options: { cutout: '80%', plugins: { legend: { position: 'bottom' } } }
    });
</script>
</body>
</html>