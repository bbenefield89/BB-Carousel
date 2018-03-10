/**********
** INDEX **
**********/
/*
**
** 1. Global Variables
**
** 2. Functions
**
** 3. Click events
**   3.a sliderSettingsHeader
**   3.b addNewImageButton
**
*/

/************************
** 1. GLOBAL VARIABLES **
************************/
const sliderSettingsHeader    = document.querySelector('.slider-settings-header');
const addNewImageButton       = document.querySelector('.add-new-image');
const sliderImageDivContainer = document.querySelector('.slider-images');

/*****************
** 2. FUNCTIONS **
*****************/
const makeAJAXRequest = (reqType, URL, formData) => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();
    
    xhr.open(reqType, URL, true);
    
    if (reqType === 'POST') {
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    }
    
    xhr.onload = () => {
      if (xhr.status === 200) {
        resolve(xhr.response);
      } else {
          reject(xhr.statusText);
      }
    };
    
    xhr.onerror = () => {
      reject(xhr.statusText);
    }
    
    xhr.send(formData);
  });
};

/********************
** 3. CLICK EVENTS **
********************/
// 3.a sliderSettingsHeader
sliderSettingsHeader.addEventListener('click', () => {
  const sliderSettingsContent  = document.querySelector('.slider-settings-content');
  const sliderSettingsHeaderH2 = document.querySelector('.slider-settings-header > h2');

  console.log(sliderSettingsHeaderH2);
  console.log(sliderSettingsHeaderH2.classList);
  
  sliderSettingsHeaderH2.classList.remove('tab-closed');
  sliderSettingsHeaderH2.classList.remove('tab-open');
  
  console.log(sliderSettingsHeaderH2.classList);
  
  if (sliderSettingsContent.style.height != '0px') {
    sliderSettingsContent.style.height  = '0px';
    sliderSettingsContent.style.padding = '0px';
    
    sliderSettingsHeaderH2.classList.add('tab-closed');
  } else {
      sliderSettingsContent.style.height  = '100%';
      sliderSettingsContent.style.padding = '10px';
      
      sliderSettingsHeaderH2.classList.add('tab-open');
  }
}); // click

// 3.b addNewImageButton
addNewImageButton.addEventListener('click', e => {
  e.preventDefault();
  
  const sliderImagesDiv = document.querySelector('.slider-images');
  const imageURL        = document.querySelector('.image-url');
  const newImageError   = document.querySelector('#new-image-error');
  const allImageInputHidden = document.querySelectorAll('.image-input-hidden');
  
  // Create elements
  const sliderImageDiv   = document.createElement('div');
  const sliderImage      = document.createElement('img');
  const removeImage      = document.createElement('span');
  const imageInputHidden = document.createElement('input');
  
  // Set necessary element attributes
  sliderImageDiv.classList.add('slider-image');
  
  sliderImage.setAttribute('src', imageURL.value);
  
  removeImage.classList.add('remove-image');
  removeImage.insertAdjacentText('afterbegin', '×');
  
  imageInputHidden.classList.add('image-input-hidden');
  imageInputHidden.setAttribute('type', 'hidden');
  imageInputHidden.setAttribute('name', 'image_input_hidden');
  imageInputHidden.setAttribute('value', imageURL.value);
  
  // If NOT valid image do nothing
  sliderImage.onerror = () => {
    newImageError.removeAttribute('hidden');
    
    return;
  }
  
  // If valid image insert new image to page
  sliderImage.onload = () => {
    const reqType = 'POST';
    const URL = '/weveloper/wp-admin/admin.php?page=bb_ajax';
    const formData = `image_input_hidden=${ imageInputHidden.value }`;
    
    newImageError.setAttribute('hidden', '');
    
    sliderImagesDiv.insertAdjacentElement('beforeend', sliderImageDiv);
    sliderImageDiv.insertAdjacentElement('beforeend', sliderImage);
    sliderImageDiv.insertAdjacentElement('beforeend', imageInputHidden);
    
    sliderImageDiv.insertAdjacentElement('beforeend', removeImage);
    
    makeAJAXRequest(reqType, URL, formData);
  }
}); // click

sliderImageDivContainer.addEventListener('click', e => {
  const removeImage = document.querySelectorAll('.remove-image');
  
  if (e.target.classList[0] === 'remove-image') {
    const reqType = 'POST';
    const URL = '/weveloper/wp-admin/admin.php?page=bb_ajax';
    const imageID = e.path[1].children[3];
    
    e.path[1].setAttribute('hidden', '');
    
    makeAJAXRequest(reqType, URL, `image_id=${ e.path[1].children[3].value }`);
  }
});
