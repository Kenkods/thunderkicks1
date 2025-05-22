document.addEventListener('DOMContentLoaded', () => {
  const sizeButtons = document.querySelectorAll('.size-btn');

  sizeButtons.forEach(button => {
    button.addEventListener('click', () => {
      // If already active, remove active styles
      if (button.classList.contains('bg-yellow-500')) {
        button.classList.remove('bg-yellow-500', 'scale-110');
        return;
      }

      // Remove active styles from all buttons
      sizeButtons.forEach(btn =>
        btn.classList.remove('bg-yellow-500', 'scale-110')
      );

      // Add active styles to clicked button
      button.classList.add('bg-yellow-500', 'scale-110');
    });
  });
});
