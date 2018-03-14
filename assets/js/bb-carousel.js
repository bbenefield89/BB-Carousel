(function bb_carousel() {
  const carousels = document.querySelectorAll('.image-carousel');
  
  // forEach
  [].forEach.call(carousels, c => {
    console.log('asdad')
    let next = document.querySelector('.next');
    let prev = document.querySelector('.previous');
    let bubblesContainer = document.querySelector('.bubbles');
    let inner = document.querySelector('.inner');
    let imgs = document.querySelectorAll('.inner img');
    let currentImageIndex = 0;
    let width = 100;
    let bubbles = [];
    let interval = start();
    
    // for
    for (let i = 0; i < imgs.length; i++) {
      let b = document.createElement('span');
      b.classList.add('bubble');
      bubblesContainer.append(b);
      bubbles.push(b);
      
      b.addEventListener('click', () => {
        currentImageIndex = i;
        switchImg();
      });
    } // endfor
    
    // switchImg()
    function switchImg() {
      inner.style.left = -width * currentImageIndex + '%';
      
      bubbles.forEach(function (b, i) {
        if (i === currentImageIndex) {
          b.classList.add('active');
        } else {
            b.classList.remove('active');
        }
      });
    } // switchImg()
    
    // start()
    function start() {
      return setInterval(() => {
        currentImageIndex++;
        
        if (currentImageIndex >= imgs.length) {
          currentImageIndex = 0;
        }
        
        switchImg();
      }, 1000);
    }
    
    // inner mouseenter
    inner.addEventListener('mouseenter', () => {
      clearInterval(interval);
    });
    
    // inner mouseleave
    inner.addEventListener('mouseleave', () => {
      interval = start();
    });
    
    // `next` button click
    next.addEventListener('click', () => {
      currentImageIndex++;
      
      if (currentImageIndex >= imgs.length) {
        currentImageIndex = 0;
      }
      
      switchImg();
    });
    
    // `prev` button click
    prev.addEventListener('click', () => {
      currentImageIndex--;
      
      if (currentImageIndex < 0) {
        currentImageIndex = imgs.length - 1;
      }
      
      switchImg();
    });
    
    switchImg();
  });
}());
