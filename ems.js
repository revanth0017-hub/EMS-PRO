import { Chart } from "@/components/ui/chart"
document.addEventListener("DOMContentLoaded", () => {
  // Show login modal on page load
  const loginModal = document.getElementById("login-modal")
  loginModal.style.display = "block"

  // Login form submission
  const loginForm = document.getElementById("login-form")
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault()
    loginModal.style.display = "none"
    showToast("Login successful! Welcome to EMS.", "success")
  })

  // Toggle password visibility
  const togglePassword = document.getElementById("toggle-password")
  const passwordInput = document.getElementById("password")

  togglePassword.addEventListener("click", function () {
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
    passwordInput.setAttribute("type", type)
    this.classList.toggle("fa-eye")
    this.classList.toggle("fa-eye-slash")
  })

  // Sidebar toggle
  const sidebarToggle = document.getElementById("sidebar-toggle")
  const sidebar = document.getElementById("sidebar")
  const mainContent = document.querySelector(".main-content")

  sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed")
    mainContent.classList.toggle("expanded")
  })

  // Navigation
  const menuItems = document.querySelectorAll(".sidebar-menu li")
  const pages = document.querySelectorAll(".page")

  menuItems.forEach((item) => {
    item.addEventListener("click", function () {
      const pageId = this.getAttribute("data-page")

      // Update active menu item
      menuItems.forEach((item) => item.classList.remove("active"))
      this.classList.add("active")

      // Show selected page
      pages.forEach((page) => page.classList.remove("active"))
      document.getElementById(`${pageId}-page`).classList.add("active")
    })
  })

  // Current date
  const currentDateElement = document.getElementById("current-date")
  const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" }
  const currentDate = new Date().toLocaleDateString("en-US", options)
  currentDateElement.textContent = currentDate

  // Modal functionality
  const modals = document.querySelectorAll(".modal")
  const closeButtons = document.querySelectorAll(".close-modal")

  // Add Employee button
  const addEmployeeBtn = document.getElementById("add-employee-btn")
  const addEmployeeModal = document.getElementById("add-employee-modal")
  const cancelAddEmployee = document.getElementById("cancel-add-employee")
  const submitAddEmployee = document.getElementById("submit-add-employee")

  if (addEmployeeBtn) {
    addEmployeeBtn.addEventListener("click", () => {
      addEmployeeModal.style.display = "block"
    })
  }

  if (cancelAddEmployee) {
    cancelAddEmployee.addEventListener("click", () => {
      addEmployeeModal.style.display = "none"
    })
  }

  if (submitAddEmployee) {
    submitAddEmployee.addEventListener("click", () => {
      addEmployeeModal.style.display = "none"
      showToast("Employee added successfully!", "success")
    })
  }

  // Quick link to add employee
  const addEmployeeLink = document.getElementById("add-employee-link")
  if (addEmployeeLink) {
    addEmployeeLink.addEventListener("click", () => {
      // Switch to employee page
      menuItems.forEach((item) => item.classList.remove("active"))
      document.querySelector('[data-page="employee"]').classList.add("active")

      // Show employee page
      pages.forEach((page) => page.classList.remove("active"))
      document.getElementById("employee-page").classList.add("active")

      // Open add employee modal
      addEmployeeModal.style.display = "block"
    })
  }

  // Close modals
  closeButtons.forEach((button) => {
    button.addEventListener("click", () => {
      modals.forEach((modal) => {
        modal.style.display = "none"
      })
    })
  })

  // Close modal when clicking outside
  window.addEventListener("click", (event) => {
    modals.forEach((modal) => {
      if (event.target === modal) {
        modal.style.display = "none"
      }
    })
  })

  // Delete confirmation
  const deleteButtons = document.querySelectorAll(".delete-btn")
  const deleteConfirmationModal = document.getElementById("delete-confirmation-modal")
  const cancelDelete = document.getElementById("cancel-delete")
  const confirmDelete = document.getElementById("confirm-delete")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.stopPropagation()
      deleteConfirmationModal.style.display = "block"
    })
  })

  if (cancelDelete) {
    cancelDelete.addEventListener("click", () => {
      deleteConfirmationModal.style.display = "none"
    })
  }

  if (confirmDelete) {
    confirmDelete.addEventListener("click", () => {
      deleteConfirmationModal.style.display = "none"
      showToast("Item deleted successfully!", "success")
    })
  }

  // Toast notification
  function showToast(message, type = "success") {
    const toast = document.getElementById("toast")
    const toastMessage = document.querySelector(".toast-message")
    const toastIcon = document.querySelector(".toast-content i")

    toastMessage.textContent = message

    if (type === "success") {
      toastIcon.className = "fas fa-check-circle"
      toastIcon.style.color = "var(--success-color)"
    } else if (type === "error") {
      toastIcon.className = "fas fa-times-circle"
      toastIcon.style.color = "var(--danger-color)"
    } else if (type === "warning") {
      toastIcon.className = "fas fa-exclamation-circle"
      toastIcon.style.color = "var(--warning-color)"
    }

    toast.style.display = "block"

    setTimeout(() => {
      toast.style.display = "none"
    }, 3000)
  }

  // Logout button
  const logoutBtn = document.getElementById("logout-btn")
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      loginModal.style.display = "block"
    })
  }

  // Initialize charts
  initializeCharts()
})

function initializeCharts() {
  // Attendance Overview Chart
  const attendanceCtx = document.getElementById("attendance-chart")
  if (attendanceCtx) {
    new Chart(attendanceCtx, {
      type: "pie",
      data: {
        labels: ["Present", "Absent", "Late", "On Leave"],
        datasets: [
          {
            data: [231, 8, 5, 4],
            backgroundColor: [
              "rgba(56, 161, 105, 0.8)",
              "rgba(229, 62, 62, 0.8)",
              "rgba(246, 173, 85, 0.8)",
              "rgba(69, 162, 158, 0.8)",
            ],
            borderColor: [
              "rgba(56, 161, 105, 1)",
              "rgba(229, 62, 62, 1)",
              "rgba(246, 173, 85, 1)",
              "rgba(69, 162, 158, 1)",
            ],
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "right",
            labels: {
              color: "#C5C6C7",
            },
          },
        },
      },
    })
  }

  // Department Chart
  const departmentCtx = document.getElementById("department-chart")
  if (departmentCtx) {
    new Chart(departmentCtx, {
      type: "bar",
      data: {
        labels: ["IT", "HR", "Finance", "Marketing", "Sales"],
        datasets: [
          {
            label: "Employees",
            data: [68, 24, 36, 42, 56],
            backgroundColor: "rgba(102, 252, 241, 0.6)",
            borderColor: "rgba(102, 252, 241, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(255, 255, 255, 0.1)",
            },
            ticks: {
              color: "#C5C6C7",
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              color: "#C5C6C7",
            },
          },
        },
        plugins: {
          legend: {
            display: false,
          },
        },
      },
    })
  }

  // Salary Chart
  const salaryChartElement = document.getElementById("salary-chart")
  if (salaryChartElement) {
    const salaryOptions = {
      series: [
        {
          name: "Salary Expenses",
          data: [42000, 47000, 52000, 58000, 65000, 63000, 68000, 72000, 76000, 82000, 85000, 90000],
        },
      ],
      chart: {
        height: 300,
        type: "area",
        fontFamily: "Inter, sans-serif",
        toolbar: {
          show: false,
        },
        background: "transparent",
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        curve: "smooth",
        width: 3,
        colors: ["#66FCF1"],
      },
      fill: {
        type: "gradient",
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.7,
          opacityTo: 0.2,
          stops: [0, 90, 100],
          colorStops: [
            {
              offset: 0,
              color: "#66FCF1",
              opacity: 0.4,
            },
            {
              offset: 100,
              color: "#66FCF1",
              opacity: 0,
            },
          ],
        },
      },
      xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        labels: {
          style: {
            colors: "#C5C6C7",
          },
        },
        axisBorder: {
          show: false,
        },
        axisTicks: {
          show: false,
        },
      },
      yaxis: {
        labels: {
          style: {
            colors: "#C5C6C7",
          },
          formatter: (value) => "$" + value.toLocaleString(),
        },
      },
      grid: {
        borderColor: "rgba(255, 255, 255, 0.1)",
        xaxis: {
          lines: {
            show: false,
          },
        },
        yaxis: {
          lines: {
            show: true,
          },
        },
      },
      tooltip: {
        theme: "dark",
        x: {
          show: false,
        },
        y: {
          formatter: (value) => "$" + value.toLocaleString(),
        },
        marker: {
          show: false,
        },
      },
    }

    // Declare ApexCharts before using it
    const ApexCharts = window.ApexCharts

    const salaryChart = new ApexCharts(salaryChartElement, salaryOptions)
    salaryChart.render()
  }

  // Weekly Attendance Chart
  const weeklyAttendanceCtx = document.getElementById("weekly-attendance-chart")
  if (weeklyAttendanceCtx) {
    new Chart(weeklyAttendanceCtx, {
      type: "line",
      data: {
        labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
        datasets: [
          {
            label: "Present",
            data: [235, 240, 238, 231, 228, 120, 80],
            borderColor: "rgba(56, 161, 105, 1)",
            backgroundColor: "rgba(56, 161, 105, 0.1)",
            tension: 0.3,
            fill: true,
          },
          {
            label: "Absent",
            data: [13, 8, 10, 17, 20, 128, 168],
            borderColor: "rgba(229, 62, 62, 1)",
            backgroundColor: "rgba(229, 62, 62, 0.1)",
            tension: 0.3,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(255, 255, 255, 0.1)",
            },
            ticks: {
              color: "#C5C6C7",
            },
          },
          x: {
            grid: {
              display: false,
            },
            ticks: {
              color: "#C5C6C7",
            },
          },
        },
        plugins: {
          legend: {
            labels: {
              color: "#C5C6C7",
            },
          },
        },
      },
    })
  }
}

