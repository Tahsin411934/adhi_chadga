<template>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar (col-3) -->
      <nav
        class="col-md-3 col-lg-3 sidebar bg-gray-200 text-gray-950 vh-100 p-3" style="margin-top: 100px;"
        :class="{ 'show-sidebar': isSidebarVisible || !isMobile }"
      >
        <h5 class="mb-4 text-dark">Admin Panel</h5>
        <ul class="nav flex-column">
          <li class="nav-item mb-3" v-for="item in menuItems" :key="item.id">
            <router-link
              v-if="!item.submenu"
              :to="item.link"
              class="nav-link d-flex align-items-center py-2"
              :class="{ active: isActiveRoute(item.link) }"
            >
              <i :class="item.icon" class="me-3"></i>
              <span class="text-dark">{{ item.label }}</span>
            </router-link>
            <div v-else>
              <a href="#" class="nav-link d-flex align-items-center py-2" @click.prevent="toggleSubmenu(item.id)">
                <i :class="item.icon" class="me-3 text-dark"></i>
                <span class="text-dark">{{ item.label }}</span>
                <i :class="item.isOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="ms-auto"></i>
              </a>
              <ul v-show="item.isOpen" class="nav flex-column ms-4 submenu">
                <li v-for="subItem in item.submenu" :key="subItem.id">
                  <router-link
                    :to="subItem.link"
                    class="nav-link d-flex align-items-center"
                    :class="{ active: isActiveRoute(subItem.link) }"
                  >
                    <i :class="subItem.icon" class="me-3"></i>
                    {{ subItem.label }}
                  </router-link>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>

      <!-- Main Content (col-9) -->
      <main class="col-md-9 col-lg-9 px-4 ">
        <button class="d-md-none position-fixed top-2 left-2 bg-gray-200 p-2 rounded toggle-btn" @click="toggleSidebar">
          <i class="fas fa-bars text-dark"></i>
        </button>
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isSidebarVisible: false,
      menuItems: [
        { id: 1, label: "Dashboard", link: "/dashboard/", icon: "fas fa-tachometer-alt" },
        {
          id: 2,
          label: "Category",
          link: "/dashboard/catagories",
          icon: "fas fa-th-list",
          isOpen: false,
          submenu: [
            { id: 21, label: "Manage Categories", link: "/dashboard/catagories", icon: "fas fa-edit" }
          ],
        },
        {
          id: 3,
          label: "Items",
          link: "/dashboard/items",
          icon: "fas fa-hamburger",
          isOpen: false,
          submenu: [
            { id: 31, label: "Manage Items", link: "/dashboard/items", icon: "fas fa-edit" }
          ],
        },
        {
          id: 4,
          label: "Orders",
          link: "/orders",
          icon: "fas fa-shopping-cart",
          isOpen: false,
          submenu: [
            { id: 41, label: "Pending Orders", link: "/orders/pending", icon: "fas fa-clock" },
            { id: 42, label: "Completed Orders", link: "/orders/completed", icon: "fas fa-check-circle" },
          ],
        },
        { id: 5, label: "Settings", link: "/settings", icon: "fas fa-cogs" },
        { id: 6, label: "Logout", link: "/logout", icon: "fas fa-sign-out-alt" },
      ],
    };
  },
  computed: {
    isMobile() {
      return window.innerWidth < 768;
    },
  },
  methods: {
    toggleSubmenu(id) {
      const item = this.menuItems.find((item) => item.id === id);
      if (item) {
        item.isOpen = !item.isOpen;
      }
    },
    toggleSidebar() {
      this.isSidebarVisible = !this.isSidebarVisible;
    },
    isActiveRoute(route) {
      return this.$route.path.startsWith(route);
    },
  },
};
</script>

<style scoped>
.sidebar {
  height: 100vh;
  overflow-y: auto;
  box-shadow: 2px 0px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
  position: fixed;
  left: 0;
  top: 0;
  background-color: #f8f9fa;
  z-index: 1050;
  width: 250px;
  transform: translateX(-100%);
}

.show-sidebar {
  transform: translateX(0);
}

@media (min-width: 768px) {
  .sidebar {
    transform: translateX(0);
  }
}

main {
  min-height: 100vh;
  margin-left: 0;
}

@media (min-width: 768px) {
  main {
    margin-left: 250px;
  }
}

.toggle-btn {
  top: 15px;
  left: 15px;
  z-index: 1100;
}
</style>
