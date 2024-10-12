const session_check = sessionStorage.getItem('blogserv_ssc');
console.log(session_check)

if(session_check==null){
    alert('User authentication required')
    location.href= '../../old_blog/blogger/login.php';
}


console.log(getCookie("blogserv_usc"))
console.log('Hello')



const blog_title = document.querySelector("#blog_title");
const image = document.getElementById("featured");
const blog_body = document.querySelector("#blog_editor");
const form = document.querySelector("form");
const tags = document.querySelector("#tags");
const categories = document.querySelector("#categories");
const logout_btn = document.querySelector('#logout__');
// console.log(logout_btn)

let imageBase64;
let secret = prepToken()

function convertImage(e) {
  console.log(e);
  const files = e.target.files[0];
  const reader = new FileReader();

  reader.addEventListener("load", () => {
    // console.log(reader.result);
    imageBase64 = reader.result;
  });

  reader.readAsDataURL(files);
}

image.addEventListener("change", convertImage);

form.addEventListener("submit", (e) => {
  e.preventDefault();
  let blog_content = {
    title: blog_title.value,
    image: imageBase64,
    content: blog_body.innerText,
    tags: tags.value,
    categories: categories.value,
    ...prepToken()
    
    // user_id: `${JSON.stringify(secret)}`
  };

 



    console.log(blog_content);

    axios.post('../blogpoint/blogapi/createblog.php', JSON.stringify(blog_content))
    .then((response) => {
      console.log(response)
    })

    




//   fetch(
//     "http://blogs.skaetch.com/blog_services/blogpoint/blog/createone",
//     configData
//   )
//   .then(async (response) => {
//       // Check if the response is not OK, then read as text
//       if (!response.ok) {
//         const text = await response.text();
//         console.log(text + " response not ok status 22");
//         // alert(text, " response not ok status 22");
//         location.href ="../index.php"
//         // throw new Error(Error `response from server:${text}`);
//       }
//       return response.json();
//     })
//     .then(data => {
//         console.log(data);
//     })
//     // .then((res) => res.json())
//     // .then((data) => {
//     //   console.log(data);
//     // })
//     // .catch((err) => {
//     //   console.log(err);
//     // });
});


// logout_btn.addEventListener('click', ()=>{
//   sessionStorage.removeItem('blogserv_ssc');
//   localStorage.removeItem('blogserv_lsc');
//   desToken();
// })