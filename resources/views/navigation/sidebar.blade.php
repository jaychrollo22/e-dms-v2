<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="/">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/document-requests">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Requests</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/document-uploads">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Uploads</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/document-copy-requests">
                <i class="icon-folder menu-icon"></i>
                <span class="menu-title">Copy Requests</span>
            </a>
        </li>
        @if(roleValidationAsAdministrator())
        <li class="nav-item">
            <a class="nav-link" href="/access-requests">
                <i class="icon-key menu-icon"></i>
                <span class="menu-title">Access Request</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
                <i class="icon-cog menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="/settings">Application</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/companies">Companies</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/departments">Departments</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/document-categories">Document Categories</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/roles">Roles</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/users">Users</a></li>
                    <li class="nav-item"> <a class="nav-link" href="/tags">Tags</a></li>
                </ul>
            </div>
        </li>
        @endif
    </ul>
</nav>
