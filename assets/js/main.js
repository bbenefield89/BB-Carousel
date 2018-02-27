/*********************
** GLOBAL VARIABLES **
*********************/
const sliderSettingsHeader = document.querySelector('.slider-settings-header');

// Click `sliderSettingsHeader`
sliderSettingsHeader.addEventListener('click', () => {
  const sliderSettingsContent = document.querySelector('.slider-settings-content');
  const sliderSettingsHeaderH2 = document.querySelector('.slider-settings-header > h2');
  
  console.log(sliderSettingsHeaderH2);
  console.log(sliderSettingsHeaderH2.classList);
  
  sliderSettingsHeaderH2.classList.remove('tab-closed');
  sliderSettingsHeaderH2.classList.remove('tab-open');
  
  console.log(sliderSettingsHeaderH2.classList);
  
  if (sliderSettingsContent.style.height != '0px') {
    sliderSettingsContent.style.height = '0px';
    sliderSettingsContent.style.padding = '0px';
    
    sliderSettingsHeaderH2.classList.add('tab-closed');
  } else {
      sliderSettingsContent.style.height = '100%';
      sliderSettingsContent.style.padding = '10px';
      
      sliderSettingsHeaderH2.classList.add('tab-open');
  }
}); // `click`
