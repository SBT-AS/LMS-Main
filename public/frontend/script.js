// Vars
let currentCourseId = null;

/* ============================================================
  PARTIAL LOADING (HEADER & FOOTER)
   ============================================================ */

async function loadPartial(id, file) {
  try {
    const res = await fetch(file);
    if (!res.ok) throw new Error(`Failed to load ${file}`);
    const html = await res.text();
    const container = document.getElementById(id);
    if (container) {
      container.innerHTML = html;

      // If we just loaded the header, we might need to re-initialize Bootstrap components 
      // if they don't auto-init (Modals usually handle themselves via data-bs attributes)
    }
  } catch (err) {
    console.error(err);
  }
}

// Load shared layout sections
loadPartial("site-header", "../partials/header.html");
loadPartial("site-footer", "../partials/footer.html");


/* ============================================================
  HERO SECTION: WORD-BY-WORD ANIMATION
   ============================================================ */
const heading = document.querySelector(".animate-words");

if (heading) {
  const nodes = Array.from(heading.childNodes);
  heading.innerHTML = "";
  let delay = 0;

  nodes.forEach((node) => {
    if (node.nodeType === Node.TEXT_NODE) {
      const words = node.textContent.trim().split(/\s+/);
      words.forEach((word) => {
        const span = document.createElement("span");
        span.className = "word";
        span.textContent = word;
        span.style.animationDelay = `${delay}s`;
        heading.appendChild(span);
        heading.appendChild(document.createTextNode(" "));
        delay += 0.08;
      });
    }
    if (node.nodeType === Node.ELEMENT_NODE && node.tagName !== "BR") {
      const span = document.createElement("span");
      span.className = `word ${node.className}`;
      span.textContent = node.textContent;
      span.style.animationDelay = `${delay}s`;
      heading.appendChild(span);
      heading.appendChild(document.createTextNode(" "));
      delay += 0.08;
    }
    if (node.nodeName === "BR") {
      heading.appendChild(document.createElement("br"));
    }
  });
}


/* ============================================================
  HOW IT WORKS SECTION: AUTO STEP ANIMATION
   ============================================================ */
const steps = document.querySelectorAll(".step-item");
const stepImage = document.getElementById("stepImage");

if (steps.length > 0 && stepImage) {
  let currentStep = 0;
  const intervalTime = 3000;

  function showStep(index) {
    steps.forEach((step, i) => {
      step.classList.toggle("active", i === index);
    });
    stepImage.style.opacity = 0;
    setTimeout(() => {
      // stepImage.src = `../assets/step-${index + 1}.svg`; // (Assumes assets exist)
      stepImage.style.opacity = 1;
    }, 200);
  }

  setInterval(() => {
    currentStep = (currentStep + 1) % steps.length;
    showStep(currentStep);
  }, intervalTime);
}

/* ============================================================
  NEWSLETTER FORM
   ============================================================ */
const joinBtn = document.getElementById("joinBtn");
if (joinBtn) {
  joinBtn.addEventListener("click", (e) => {
    e.preventDefault();
    alert("Thank you for joining our newsletter!");
  });
}

/* ============================================================
  FOOTER REVEAL
   ============================================================ */
const revealText = document.querySelector(".reveal-text");
if (revealText) {
  const observer = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        revealText.classList.add("in-view");
        observer.disconnect();
      }
    },
    { threshold: 0.3 }
  );
  observer.observe(revealText);
}

/* ============================================================
  TESTIMONIALS
   ============================================================ */
const testimonials = [
  {
    metric: "3×",
    metricText: "faster learning through real projects",
    quote: "Educater helped me move from just watching tutorials to actually building projects.",
    author: "Aarav Sharma",
    role: "Frontend Developer (Student)",
    avatar: "../assets/avatar-placeholder.jpg",
  },
  {
    metric: "2.5×",
    metricText: "better confidence in real-world coding",
    quote: "The project-based approach made everything click. I finally understand how things work together.",
    author: "Neha Patel",
    role: "CS Undergraduate",
    avatar: "../assets/avatar-placeholder.jpg",
  },
  {
    metric: "100%",
    metricText: "hands-on learning experience",
    quote: "This feels closer to real development than any course I’ve taken before.",
    author: "Rohit Verma",
    role: "Self-taught Developer",
    avatar: "../assets/avatar-placeholder.jpg",
  },
];

let currentTestimonial = 0;
const card = document.querySelector(".testimonial-card");
const metricEl = document.getElementById("testimonialMetric");

if (card && metricEl) {
  const metricTextEl = document.getElementById("testimonialMetricText");
  const quoteEl = document.getElementById("testimonialQuote");
  const authorEl = document.getElementById("testimonialAuthor");
  const avatarEl = document.getElementById("testimonialAvatar");

  function showTestimonial(index) {
    const t = testimonials[index];
    card.classList.add("fade-out");
    setTimeout(() => {
      metricEl.textContent = t.metric;
      metricTextEl.textContent = t.metricText;
      quoteEl.textContent = `“${t.quote}”`;
      authorEl.innerHTML = `<strong>${t.author}</strong><br />${t.role}`;
      // avatarEl.src = t.avatar;
      card.classList.remove("fade-out");
    }, 300);
  }

  setInterval(() => {
    currentTestimonial = (currentTestimonial + 1) % testimonials.length;
    showTestimonial(currentTestimonial);
  }, 5000);
}


/* ============================================================
  DYNAMIC GRID (UPDATED)
   ============================================================ */
// Note: In a real app this would come from an API
const courses = [
  {
    id: "web-dev",
    title: "Web Development Foundations",
    category: "Web Development",
    level: "Beginner",
    price: 2499,
    description: "Learn HTML, CSS, and JavaScript by building real-world layouts.",
    image: "https://d3njjcbhbojbot.cloudfront.net/api/utilities/v1/imageproxy/https://d15cw65ipctsrr.cloudfront.net/85/b322ebed824235921710c15744aa83/Image_1200x1200.png?auto=format%2C%20compress%2C%20enhance&dpr=3&w=320&h=180&fit=crop&q=50",
  },
  {
    id: "js-projects",
    title: "JavaScript Projects",
    category: "JavaScript",
    level: "Intermediate",
    price: 2999,
    description: "Build interactive apps and strengthen problem-solving skills.",
    image: "https://d3njjcbhbojbot.cloudfront.net/api/utilities/v1/imageproxy/https://d15cw65ipctsrr.cloudfront.net/85/b322ebed824235921710c15744aa83/Image_1200x1200.png?auto=format%2C%20compress%2C%20enhance&dpr=3&w=320&h=180&fit=crop&q=50",
  },
  {
    id: "fullstack",
    title: "Full-Stack Applications",
    category: "Full Stack",
    level: "Advanced",
    price: 3999,
    description: "Create scalable applications using modern backend technologies.",
    image: "https://d3njjcbhbojbot.cloudfront.net/api/utilities/v1/imageproxy/https://d15cw65ipctsrr.cloudfront.net/85/b322ebed824235921710c15744aa83/Image_1200x1200.png?auto=format%2C%20compress%2C%20enhance&dpr=3&w=320&h=180&fit=crop&q=50",
  }
];

const coursesGrid = document.getElementById("coursesGrid");

if (coursesGrid) {
  coursesGrid.innerHTML = courses
    .map(
      (course) => `
<div class="col-md-6 col-lg-4">
    <div class="course-card h-100 d-flex flex-column">
        <div class="course-media position-relative">
            <img src="${course.image}" class="course-image" alt="${course.title}" />
            
            <a href="course-preview.html?id=${course.id}" class="preview-btn text-decoration-none">
                <i class="bi bi-play-fill me-1"></i> Preview
            </a>
        </div>

        <div class="p-4 flex-grow-1 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge-custom category">${course.category}</span>
                <span class="text-white fw-bold">₹${course.price}</span>
            </div>

            <h4 class="mb-2 fs-5"><a href="course-detail.html?id=${course.id}">${course.title}</a></h4>
            <p class="text-muted small mb-0 flex-grow-1">${course.description}</p>
            
            <div class="mt-3 pt-3 border-top border-secondary d-flex justify-content-between align-items-center">
                <span class="text-muted small text-uppercase fw-bold">${course.level}</span>
                <a href="course-detail.html?id=${course.id}" class="btn-link small">Details &rarr;</a>
            </div>
        </div>
    </div>
</div>
`
    )
    .join("");
}
