document.addEventListener('DOMContentLoaded', () => {
    const dropbtn = document.querySelector('.dropbtn');
    const dropdownContent = document.querySelector('.dropdown-content');

    dropbtn.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdownContent.classList.toggle('show');
      dropbtn.classList.toggle('active');
    });

    window.addEventListener('click', () => {
      if (dropdownContent.classList.contains('show')) {
        dropdownContent.classList.remove('show');
        dropbtn.classList.remove('active');
      }
    });
  });