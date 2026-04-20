<nav style="background-color: #99b4d6; color: aliceblue;">
    <i class='bx bx-menu toggle-sidebar'></i>
    <form action="#">
        <div class="search-form">
            <input type="text" placeholder="Search...">
            <i class='bx bx-search icon' style="color: #5F84A2;"></i>
        </div>
    </form>
    <!-- <a href="#" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
				<span class="badge"  style="background-color: #5F84A2; color: aliceblue;">5</span>
			</a> -->
    <a href="#" class="nav-link">
        <i class='bx bxs-message-square-dots icon'></i>
        <span class="badge" style="background-color: #5F84A2; color: aliceblue;">2</span>
    </a>
    <div class="profile">
        <div class="profile_details">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60"
                alt="">
            <p class="profile-name">Pyone</p>
        </div>
        <ul class="profile-link">
            <li><a href="#"><i class='bx bxs-user-circle icon'></i> Profile</a></li>
            <li><a href="#"><i class='bx bxs-cog'></i> Edit Profile</a></li>
            <li><a href="#"><i class='bx bxs-log-out-circle'></i> Logout</a></li>
        </ul>
    </div>
</nav>
<script>
// PROFILE DROPDOWN
const profile = document.querySelector('nav .profile');
const imgProfile = profile.querySelector('.profile-name');
const dropdownProfile = profile.querySelector('.profile-link');

imgProfile.addEventListener('click', function() {
    dropdownProfile.classList.toggle('show');
})

// MENU
const allMenu = document.querySelectorAll('main .content-data .head .menu');

allMenu.forEach(item => {
    const icon = item.querySelector('.icon');
    const menuLink = item.querySelector('.menu-link');

    icon.addEventListener('click', function() {
        menuLink.classList.toggle('show');
    })
})

window.addEventListener('click', function(e) {
    if (e.target !== imgProfile) {
        if (e.target !== dropdownProfile) {
            if (dropdownProfile.classList.contains('show')) {
                dropdownProfile.classList.remove('show');
            }
        }
    }

    allMenu.forEach(item => {
        const icon = item.querySelector('.icon');
        const menuLink = item.querySelector('.menu-link');

        if (e.target !== icon) {
            if (e.target !== menuLink) {
                if (menuLink.classList.contains('show')) {
                    menuLink.classList.remove('show')
                }
            }
        }
    })
})
</script>