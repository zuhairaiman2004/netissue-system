<?php
session_start();
include('db_conn.php');

// 1. Security Check: Pastikan user dah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. FIX: Ambil username terus dari database untuk elakkan error "Undefined array key"
$user_info_query = mysqli_query($conn, "SELECT username FROM users WHERE user_id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_info_query);
$username = $user_data['username'] ?? 'User'; 

// 3. Stats for User
$total_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE user_id = '$user_id'");
$pending_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE user_id = '$user_id' AND status='Pending'");
$resolved_q = mysqli_query($conn, "SELECT COUNT(*) as t FROM issues WHERE user_id = '$user_id' AND status='Resolved'");

$total = mysqli_fetch_assoc($total_q)['t'] ?? 0;
$pending = mysqli_fetch_assoc($pending_q)['t'] ?? 0;
$resolved = mysqli_fetch_assoc($resolved_q)['t'] ?? 0;

// 4. Fetch User's Issues List
$query = "SELECT * FROM issues WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetIssue | My Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --user-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        body { background: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
        
        /* Navbar Elite */
        .navbar { background: var(--user-gradient) !important; border-bottom: 3px solid rgba(255,255,255,0.1); }
        
        /* Welcome Card */
        .welcome-card { background: var(--user-gradient); border-radius: 24px; color: white; border: none; overflow: hidden; position: relative; }
        .welcome-card .bi-shield-lock { position: absolute; right: -20px; bottom: -20px; font-size: 10rem; opacity: 0.1; transform: rotate(-15deg); }
        
        /* Stats Cards */
        .stat-card { border: none; border-radius: 20px; background: white; transition: 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        
        /* Issue Cards */
        .issue-card { border: none; border-radius: 20px; background: white; margin-bottom: 1.2rem; border-left: 6px solid #e2e8f0; transition: 0.3s; }
        .issue-card:hover { border-left-color: #4f46e5; transform: scale(1.005); }
        
        .status-badge { border-radius: 10px; padding: 6px 14px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; }
        
        .btn-report { background: white; color: #4f46e5; font-weight: 700; border-radius: 12px; padding: 12px 24px; transition: 0.3s; border: none; }
        .btn-report:hover { background: #f1f5f9; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        /* Modal Customization */
        .modal { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); }
        .modal-content { border-radius: 28px; border: none; padding: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-lg py-3 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold fs-4" href="#">
            <i class="bi bi-rocket-takeoff-fill me-2"></i> NETISSUE
        </a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-block small">Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="card welcome-card p-4 p-md-5 mb-5 shadow-lg" data-aos="fade-down">
        <div class="row align-items-center">
            <div class="col-md-8 position-relative" style="z-index: 2;">
                <h1 class="fw-bold display-5">Hi, <?php echo htmlspecialchars($username); ?>! 👋</h1>
                <p class="fs-5 opacity-75">Facing any technical issues? We're here to help you get back on track.</p>
                <button class="btn btn-report mt-3 shadow-lg" data-bs-toggle="modal" data-bs-target="#reportModal">
                    <i class="bi bi-plus-lg me-2"></i> Create Support Ticket
                </button>
            </div>
            <i class="bi bi-shield-lock"></i>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="card stat-card p-4 h-100">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary"><i class="bi bi-clipboard2-data fs-3"></i></div>
                    <div><small class="text-muted fw-bold">TOTAL REPORTS</small><h3 class="fw-bold mb-0"><?php echo $total; ?></h3></div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
            <div class="card stat-card p-4 h-100 text-warning">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3 text-warning"><i class="bi bi-clock-history fs-3"></i></div>
                    <div><small class="text-muted fw-bold">WAITING</small><h3 class="fw-bold mb-0"><?php echo $pending; ?></h3></div>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="300">
            <div class="card stat-card p-4 h-100 text-success">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3 text-success"><i class="bi bi-check2-circle fs-3"></i></div>
                    <div><small class="text-muted fw-bold">RESOLVED</small><h3 class="fw-bold mb-0"><?php echo $resolved; ?></h3></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">My Support History</h4>
                <button onclick="window.location.reload();" class="btn btn-link text-decoration-none text-muted small"><i class="bi bi-arrow-clockwise"></i> Sync Data</button>
            </div>

            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $status = $row['status'];
                    $color = ($status == 'Pending') ? 'warning' : (($status == 'In Progress') ? 'info text-white' : 'success text-white');
                ?>
                <div class="card issue-card shadow-sm p-4 border-0">
                    <div class="d-md-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <span class="badge bg-<?php echo $color; ?> status-badge mb-2"><?php echo $status; ?></span>
                            <h5 class="fw-bold mb-1 text-dark"><?php echo $row['category']; ?></h5>
                            <p class="text-secondary small mb-3"><?php echo htmlspecialchars($row['description']); ?></p>
                            <div class="d-flex align-items-center text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-clock me-1"></i> Posted on <?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?>
                            </div>
                        </div>
                        
                        <?php if(!empty($row['admin_remarks'])): ?>
                        <div class="mt-3 mt-md-0 ms-md-4 p-3 bg-light rounded-4 border-start border-primary border-4" style="min-width: 250px;">
                            <small class="fw-bold text-primary d-block mb-1 text-uppercase"><i class="bi bi-info-circle-fill me-1"></i> Admin Solution:</small>
                            <small class="text-dark fw-medium"><?php echo htmlspecialchars($row['admin_remarks']); ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5 bg-white rounded-5 shadow-sm border-2 border-dashed">
                    <i class="bi bi-chat-square-dots text-muted display-1 opacity-25"></i>
                    <p class="text-muted mt-3">You haven't submitted any reports yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4" data-aos="fade-left">
            <div class="card border-0 shadow-sm p-4 rounded-4 bg-white sticky-top" style="top: 100px;">
                <h6 class="fw-bold border-bottom pb-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Help Center</h6>
                <div class="mt-3">
                    <div class="d-flex mb-3">
                        <i class="bi bi-1-circle text-primary me-2"></i>
                        <p class="small text-muted mb-0">Submit a ticket with a clear description.</p>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-2-circle text-primary me-2"></i>
                        <p class="small text-muted mb-0">Our admins will review and update the status.</p>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-3-circle text-primary me-2"></i>
                        <p class="small text-muted mb-0">Check the 'Admin Solution' for your fix details.</p>
                    </div>
                </div>
                <hr>
                <div class="text-center small text-muted italic">Need more help? <br> Contact IT Support at Ext 104</div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-2xl">
            <form action="submit_issue.php" method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold fs-4">New Support Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">WHAT'S THE ISSUE?</label>
                        <select name="category" class="form-select border-0 bg-light py-3 rounded-3 shadow-none" required>
                            <option value="Network/Wi-Fi">Network / Wi-Fi Connection</option>
                            <option value="Hardware/PC">Hardware / PC Problem</option>
                            <option value="Software/App">Software / Application Issue</option>
                            <option value="Others">Something Else</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">PROBLEM DESCRIPTION</label>
                        <textarea name="description" class="form-control border-0 bg-light py-3 rounded-3 shadow-none" rows="4" placeholder="Describe what happened..." required></textarea>
                    </div>
                    <button type="submit" name="submit_report" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg">
                        Send Report Now <i class="bi bi-send-fill ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
</body>
</html>
