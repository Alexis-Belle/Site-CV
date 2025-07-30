document.addEventListener("DOMContentLoaded", () => {
  const pageProject = document.querySelector(".page-projets");
  if (pageProject) {
    pageProject.classList.add("visible");
  }

  const singleProject = document.querySelector(".single-project");
  if (singleProject) {
    singleProject.classList.add("visible");
  }
});


document.addEventListener("DOMContentLoaded", () => {
  const projects = document.querySelectorAll(".project");

  if (projects.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target); // Empêche de rejouer l'animation
        }
      });
    }, { threshold: 0.2 });

    projects.forEach(project => observer.observe(project));
  }
});
