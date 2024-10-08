document.querySelectorAll('.read-more-link').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault(); 
  
      const moreText = this.closest('.whyChooseUs-content').querySelector('.more-text');
      
      if (moreText.style.display === 'none' || !moreText.style.display) {
        moreText.style.display = 'block';
        this.textContent = 'Less'; 
      } else {
        moreText.style.display = 'none';
        this.textContent = 'More'; 
      }
    });
  });
  