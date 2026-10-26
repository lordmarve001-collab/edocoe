<nav class="navbar-modern-top">
    <div class="container-fluid">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="appsummary">
                    <img src="../<?php echo $logos?>" style="height: 60px;">
                    <span><?php echo htmlentities($orgname); ?></span>
                </a>
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="appsummary"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="personaldata"><i class="fas fa-user-edit"></i> Profile</a></li>
                    <li><a href="logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
                <!-- After the navbar-brand or before user dropdown -->
<div class="appid-badge">
    <i class="fas fa-id-card"></i> 
    ID: <?php echo isset($_SESSION['staffid']) ? $_SESSION['staffid'] : ''; ?>
</div>
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name"><?php echo htmlentities($fulname); ?></span>
                        <span class="user-id"><?php echo htmlentities($userid); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    :root {
        --primary: #2b5f5a;
        --primary-dark: #1e4541;
        --primary-light: #4a7f7a;
        --primary-soft: #e8f3f1;
    }
    
    .navbar-modern-top {
        background: linear-gradient(135deg, #1a2a2a 0%, #1e3a35 100%);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        padding: 0.8rem 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid var(--primary);
    }
    
    .nav-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        padding: 0 20px;
    }
    
    .nav-brand a {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .nav-brand a i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .mobile-menu-btn {
        display: none;
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
    }
    
    .nav-menu {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    
    .nav-links {
        display: flex;
        list-style: none;
        gap: 10px;
        margin: 0;
        padding: 0;
    }
    
    .nav-links li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        border-radius: 40px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-links li a:hover {
        background: rgba(43,95,90,0.3);
        color: white;
        transform: translateY(-2px);
    }
    
    .nav-links li a.logout-btn {
        background: rgba(220,38,38,0.2);
    }
    
    .nav-links li a.logout-btn:hover {
        background: rgba(220,38,38,0.4);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.1);
        padding: 6px 16px;
        border-radius: 50px;
    }
    
    .user-avatar i {
        font-size: 2rem;
        color: var(--primary);
    }
    
    .user-details {
        display: flex;
        flex-direction: column;
    }
    
    .user-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: white;
    }
    
    .user-id {
        font-size: 0.65rem;
        color: rgba(255,255,255,0.7);
    }
    
    @media (max-width: 768px) {
        .mobile-menu-btn {
            display: block;
        }
        
        .nav-menu {
            display: none;
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
        }
        
        .nav-menu.active {
            display: flex;
        }
        
        .nav-links {
            flex-direction: column;
            width: 100%;
        }
        
        .nav-links li a {
            padding: 8px 0;
        }
        
        .user-info {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');
        
        if (mobileBtn && navMenu) {
            mobileBtn.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });
        }
    });
</script>