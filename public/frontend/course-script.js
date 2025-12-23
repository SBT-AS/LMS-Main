const params = new URLSearchParams(window.location.search);
const courseId = params.get("id");

if (!courseId) {
  alert("Course not found");
}

const courses = [
  {
    id: "web-dev",
    title: "Web Development Foundations",
    category: "Web Development",
    level: "Beginner",
    price: 2499,
    description:
      "Learn HTML, CSS, and JavaScript by building real-world layouts.",
    duration: "6 weeks",
    projects: 5,
    materials: {
      videos: 18,
      quizzes: 6,
      pdfs: 4,
    },
    instructor: {
      name: "Aarav Sharma",
      role: "Senior Frontend Engineer",
    },
    image: "../assets/course-web.png",
    previewVideo: "../assets/preview-web.mp4",
    purchased: false,
  },
  {
    id: "js-projects",
    title: "JavaScript Projects",
    category: "JavaScript",
    level: "Intermediate",
    price: 2999,
    description:
      "Build interactive apps and strengthen problem-solving skills.",
    duration: "5 weeks",
    projects: 6,
    materials: {
      videos: 15,
      quizzes: 5,
      pdfs: 3,
    },
    instructor: {
      name: "Neha Patel",
      role: "Frontend Developer",
    },
    image: "../assets/course-js.png",
    previewVideo: "../assets/preview-js.mp4",
    purchased: false,
  },

  {
    id: "fullstack",
    title: "Full-Stack Applications",
    category: "Full Stack",
    level: "Advanced",
    price: 3999,
    description:
      "Create scalable applications using modern backend technologies.",
    duration: "8 weeks",
    projects: 7,
    materials: {
      videos: 24,
      quizzes: 8,
      pdfs: 6,
    },
    instructor: {
      name: "Rohit Verma",
      role: "Senior Software Engineer",
    },
    image: "../assets/course-fullstack.png",
    previewVideo: "../assets/preview-fullstack.mp4",
    purchased: false,
  },
];

const course = courses[courseId];

if (!course) {
  alert("Course not found");
}

document.getElementById("courseTitle").textContent = course.title;
document.getElementById("courseCategory").textContent = course.category;
document.getElementById("courseDescription").textContent = course.description;
document.getElementById("coursePrice").textContent = `₹${course.price}`;

document.getElementById("instructorName").textContent =
  course.instructor.name;
document.getElementById("instructorRole").textContent =
  course.instructor.role;

const materials = document.getElementById("courseMaterials");
materials.innerHTML = `
  <li>📺 ${course.materials.videos} Videos</li>
  <li>📝 ${course.materials.quizzes} Quizzes</li>
  <li>📄 ${course.materials.pdfs} PDFs</li>
  <li>🛠️ ${course.materials.projects} Projects</li>
`;

const actionBtn = document.getElementById("courseActionBtn");

if (course.purchased) {
  actionBtn.textContent = "Go to Dashboard";
  actionBtn.onclick = () => {
    window.location.href = "/dashboard.html";
  };
} else {
  actionBtn.textContent = "Buy Now";
  actionBtn.onclick = () => {
    alert("Payment coming soon 🚀");
  };
}

/* ============================================================
  Course Bought BUTTON LOGIC
   ============================================================ */


const purchasedCourses = ["js-projects"]; // mock data

const buyBtn = document.getElementById("buyBtn");

if (purchasedCourses.includes(courseId)) {
  buyBtn.textContent = "Go to Dashboard";
  buyBtn.onclick = () => {
    window.location.href = "/dashboard.html";
  };
} else {
  buyBtn.textContent = "Buy Now";
  buyBtn.onclick = () => {
    alert("Payment flow coming soon 🚀");
  };
}