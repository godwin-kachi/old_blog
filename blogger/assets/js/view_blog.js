const viewer = document.querySelector('.show_blogs');
const urlParams = new URLSearchParams(window.location.search);
const p_id = urlParams.get('blog_id')

// console.log(urlParams)
// console.log(p_id)


// function queryUrl(param) {
//     const urlParams = new URLSearchParams(window.location.search);
//     return urlParams.get(param)
// }

// const pid = queryUrl('id');

axios.get(`../blogpoint/blogapi/getblog.php?blog_id=${p_id}`)
// axios.post(`../blogpoint/blogapi/blog/getone/${p_id}`, {blog_id: p_id})
.then((response)=> {
    console.log(response)
})
.catch((err) => {
    console.log(err)
})