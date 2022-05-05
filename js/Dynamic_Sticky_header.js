document.addEventListener('DOMContentLoaded', () => {
//Create smooth transition for header in case stylesheets don't currenty have any tranistions
function createClass(name,rules){
    var style = document.createElement('style');
    style.type = 'text/css';
    document.getElementsByTagName('head')[0].appendChild(style);
    if(!(style.sheet||{}).insertRule){
        (style.styleSheet || style.sheet).addRule(name, rules);
      }
    else{
        style.sheet.insertRule(name+"{"+rules+"}",0);
      }
}
createClass('header',"transition: top 0.5s ease-in-out;");

//set inital screen view that is on top so it is "0"
var isScrolledDown = 0;
//Find header element from DOM and read css file
var headerTop = document.querySelector("header");
const style = getComputedStyle(headerTop);
//Transform css values to real numbers and set new css values dynamically
//Hide header by setting its height to minus "itself"
const orginalHeaderHeightNumber = parseInt(style.height.replace('px', ''), 10);
const hideHeader = orginalHeaderHeightNumber - (orginalHeaderHeightNumber * 2);


if(document.getElementsByTagName("header").length >= 1){

window.addEventListener("scroll", function() {

  var scrollTopUp = document.documentElement.scrollTop;


  if (scrollTopUp > isScrolledDown) {
    headerTop.style.top = `${hideHeader.toString()}px`;
  }

  else {
    headerTop.style.top = "0px";
  }
  isScrolledDown = scrollTopUp;
})
} else {
  console.log("DOM doesn't have a header element!")
}
})