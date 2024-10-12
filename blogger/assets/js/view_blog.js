const session_check = sessionStorage.getItem('blogserv_ssc');
console.log(session_check)

if(!session_check){
    alert('User authentication required')
    location.href= '../../old_blog/blogger/login.php';
}


const viewer = document.querySelector('.show_blogs');
const header = document.querySelector('#blog_title');
const blog_body = document.querySelector('#blog_body');
const blog_tags = document.querySelector('#blog_tags');
const blog_img = document.querySelector('#blog_img')
const blog_cat = document.querySelector('#blog_cat')
const urlParams = new URLSearchParams(window.location.search);
const p_id = urlParams.get('blog_id');
// const Out_btn = document.querySelector('.xlout');
// console.log(Out_btn)
console.log(blog_body)

// console.log(urlParams)
// console.log(p_id)



axios.get(`../blogpoint/blogapi/getblog.php?blog_id=${p_id}`)
.then((response)=> {
    header.innerHTML = response.data.result.title;
    blog_body.innerHTML = response.data.result.content;
    blog_img.src = "assets/img/uploads/" + response.data.result.image;
    blog_tags.innerHTML = response.data.result.tags;
    blog_cat.innerHTML = response.data.result.categories;

    console.log(blog_img.src)

    console.log(response)
})
.catch((err) => {
    console.log(err)
})


function logOut(){
    sessionStorage.removeItem('loggedInUser')
    location.href='../../old_blog/blogger/login.php';
}