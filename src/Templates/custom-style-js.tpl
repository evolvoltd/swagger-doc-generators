
  // # Custom JS
  var image = document.getElementsByTagName("img")[0];
  image.setAttribute("src", "{{config('l5-swagger.css.logo')}}");
  image.setAttribute("width", "125px");
  image.setAttribute("height", "auto");

  var sec = document.querySelector("section");
  var span = document.createElement("span");
  span.innerText="{{config('l5-swagger.api.title')}}";
  span.style.color="white";
  span.style.fontFamily="sans-serif";
  span.style.fontSize="20px";
  span.style.fontWeight="bold";
  var interval = setInterval(function(){
  sec = document.querySelector(".schemes.wrapper.block.col-12");
  if(sec) { sec.prepend(span); clearInterval(interval); }
  }, 500);
  // # Custom JS end