
// function getBlogs(){
//   fetch('http://blogs.skaetch.com/blog_services/blogpoint/blog/getall')
//   .then((res) =>res.json())
//   .then(data => displayBlogs(data.result))
//   .catch(err => alert(err))
// }

// getBlogs()

// function displayBlogs(items){
//   let data_target = document.getElementById("blog_content");
//   items.forEach(posts => {
//     let view_pane = `
//   <tr class="text-gray-700 dark:text-gray-400">
//                   <td class="px-4 py-3" >
//                         <p class="font-semibold" id="blog_title">${
//                           posts.title
//                         }</p>
//                   </td>
//                   <td class="px-4 py-3 text-xs" style="text-wrap: wrap; text-align: justify;">
//                     <a href="#">${posts.content.slice(0, 100) + " ..."}</a>
                    
//                   </td>
//                   <td class="px-4 py-3 text-sm" id="blog_published">
//                     ${posts.updated_at.slice(0, 10)}
//                   </td>
//                 </tr>
//   `;
//     data_target.insertAdjacentHTML("beforeend", view_pane);
    
//   });

  
// }

// const getter = require('axios');

// function displayBlogs() {
//     let data_target = document.getElementById("blog_content");

//     getter.get('http://localhost/old_blog/blogpoint/blogapi/getblogs.php');


  
// }