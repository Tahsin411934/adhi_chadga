<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <nav
      :class="[
        'sidebar bg-gray-200 text-gray-950 vh-100 p-3',
        { 'd-none d-md-block': !isSidebarVisible },
        { 'position-fixed w-100 z-50 top-0': isSidebarVisible && isMobile },
      ]"
    >
      <h5 class="mb-4 text-dark">Admin Panel</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-3" v-for="item in menuItems" :key="item.id">
          <!-- Main Link with Icon -->
          <router-link
            v-if="!item.submenu"
            :to="item.link"
            class="nav-link d-flex align-items-center py-2"
            :class="{ active: isActiveRoute(item.link, item.submenu) }"
          >
            <i :class="item.icon" class="me-3"></i>
            <span class="text-dark">{{ item.label }}</span>
          </router-link>

          <!-- Submenu (Collapsible) -->
          <div v-else>
            <a
              href="#"
              class="nav-link d-flex align-items-center py-2"
              @click.prevent="toggleSubmenu(item.id)"
            >
              <i :class="item.icon" class="me-3 text-dark"></i>
              <span class="text-dark">{{ item.label }}</span>
              <i
                :class="item.isOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                class="ms-auto"
              ></i>
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

    <!-- Hamburger Menu -->
    <button
      class="d-md-none position-fixed top-2 left-2 bg-gray-200 p-2 rounded"
      @click="toggleSidebar"
    >
      <i class="fas fa-bars text-dark"></i>
    </button>

    <!-- Main Content -->
    <div class="content flex-grow-1 p-4">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isSidebarVisible: false,
      menuItems: [
        { id: 1, label: "Dashboard", link: "/dashboard/", icon: "fas fa-tachometer-alt", active: true },
        {
          id: 2,
          label: "Category",
          link: "/category",
          icon: "fas fa-th-list",
          active: false,
          isOpen: false,
          submenu: [
            { id: 2, label: "Manage Categories", link: "/dashboard/catagories", icon: "fas fa-edit" },
          ],
        },
        {
          id: 3,
          label: "Items",
          link: "/items",
          icon: "fas fa-hamburger",
          active: false,
          isOpen: false,
          submenu: [
            { id: 2, label: "Manage Items", link: "/dashboard/items", icon: "fas fa-edit" },
          ],
        },
        {
          id: 4,
          label: "Orders",
          link: "/orders",
          icon: "fas fa-shopping-cart",
          active: false,
          isOpen: false,
          submenu: [
            { id: 1, label: "Pending Orders", link: "/orders/pending", icon: "fas fa-clock" },
            { id: 2, label: "Completed Orders", link: "/orders/completed", icon: "fas fa-check-circle" },
          ],
        },
        { id: 5, label: "Settings", link: "/settings", icon: "fas fa-cogs", active: false },
        { id: 6, label: "Logout", link: "/logout", icon: "fas fa-sign-out-alt", active: false },
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
    isActiveRoute(route, submenu) {
      // Check if the current route is part of the main route or any submenu
      if (submenu) {
        return submenu.some(subItem => this.$route.path.startsWith(subItem.link));
      }
      return this.$route.path === route;
    },
  },
};
</script>

<style scoped>
.sidebar {
  width: 250px;
  box-shadow: 2px 0px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.sidebar .nav-link {
  color: #080808;
  transition: color 0.3s, background-color 0.3s;
}

.sidebar .nav-link:hover {
  background-color: #e9ecef;
  color: #212529;
  border-radius: 5px;
}

.sidebar .nav-link.active {
  background-color: #cca106 !important;
  color: #fff;
  border-radius: 5px;
}

.sidebar .submenu {
  transition: all 0.3s ease-in-out;
}

.sidebar .submenu .nav-link {
  color: #080808;
  font-size: 13px;
}

.sidebar .submenu .nav-link:hover {
  color: #212529;
}

.sidebar .nav-item {
  margin-bottom: 1.5rem;
}

.sidebar h5 {
  font-weight: bold;
}

@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-250px);
    width: 250px;
  }

  .sidebar.d-md-block {
    transform: translateX(0);
  }

  .position-fixed {
    z-index: 50;
  }
}
</style>
