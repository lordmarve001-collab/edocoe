<div class="left-sidebar-modern">
    <div class="sidebar-content">
        <div class="sidebar-nav">
            <ul class="side-nav-modern">
                <li>
                    <a href="appsummary">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="active">
                    <a href="personaldata">
                        <i class="fas fa-address-card"></i>
                        <span>Biodata</span>
                    </a>
                </li>
                <?php
                $paycht_cert = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND apptype='CERT'"));
                if($paycht_cert > 0) { ?>
                <li>
                    <a href="certupload">
                        <i class="fas fa-file-alt"></i>
                        <span>CERT. Documents</span>
                    </a>
                </li>
                <?php } ?>
                
                <?php
                $paycht_nd = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND apptype='ND'"));
                if($paycht_nd > 0) { ?>
                <li>
                    <a href="ndupload">
                        <i class="fas fa-file-alt"></i>
                        <span>ND Documents</span>
                    </a>
                </li>
                <?php } ?>
                
                <?php
                $paycht_jchew = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='".$_SESSION['staffid']."' AND apptype='JCHEW'"));
                if($paycht_jchew > 0) { ?>
                <li>
                    <a href="jchewupload">
                        <i class="fas fa-file-alt"></i>
                        <span>JCHEW Documents</span>
                    </a>
                </li>
                <?php } ?>
                
                <li>
                    <a href="applicantoleve">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Olevel Data</span>
                    </a>
                </li>
                
                <?php
                $stanam = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM applicant WHERE applid='$staffid' AND status='submitted'"));
                if($stanam > 0) { ?>
                <li>
                    <a href="passportupdate">
                        <i class="fas fa-camera"></i>
                        <span>Update Passport</span>
                    </a>
                </li>
                <?php } ?>
                
                <?php
                $stanam = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM studentpayment WHERE studentid='$staffid' AND status='Rejected'"));
                if($stanam > 0) { ?>
                <li>
                    <a href="evidence_update">
                        <i class="fas fa-camera"></i>
                        <span>Update Evidence of Payment</span>
                    </a>
                </li>
                <?php } ?>
                
                <li>
                    <a href="applicparent">
                        <i class="fas fa-users"></i>
                        <span>Parent Detail</span>
                    </a>
                </li>
                
                <li class="divider"></li>
                
                <li>
                    <a href="../logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .left-sidebar-modern {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        position: sticky;
        top: 80px;
    }
    
    .side-nav-modern {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .side-nav-modern li {
        margin: 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .side-nav-modern li:last-child {
        border-bottom: none;
    }
    
    .side-nav-modern li.divider {
        border-top: 1px solid #e2e8f0;
        margin: 8px 0;
    }
    
    .side-nav-modern li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        color: #4a5568;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .side-nav-modern li a i {
        width: 22px;
        font-size: 1.1rem;
        color: var(--primary);
        transition: all 0.3s ease;
    }
    
    .side-nav-modern li a:hover {
        background: var(--primary-soft);
        color: var(--primary-dark);
        padding-left: 25px;
    }
    
    .side-nav-modern li a:hover i {
        transform: scale(1.1);
    }
    
    .side-nav-modern li.active a {
        background: linear-gradient(95deg, var(--primary-soft), #fff);
        color: var(--primary-dark);
        border-right: 3px solid var(--primary);
    }
    
    @media (max-width: 768px) {
        .left-sidebar-modern {
            position: relative;
            top: 0;
            margin-bottom: 20px;
        }
        .side-nav-modern li a {
            padding: 10px 15px;
            font-size: 0.8rem;
        }
    }
</style>