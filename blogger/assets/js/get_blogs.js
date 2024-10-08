
const blog_viewer = document.getElementById('blog_content')

axios.get('../blogpoint/blogapi/getblogs.php')
// axios.get('old_blog/blogpoint/blog/getall')
.then((response) => {
    let holder=response.data.result;
    console.log(response.data.result);
    holder.forEach((blog) => {
         let blog_view =`<tr class="text-gray-700 dark:text-gray-400">
                      <td class="px-4 py-3" >
                            <p class="font-semibold" id="blog_title">${blog.title}</p>
                      </td>
                      <td class="px-4 py-3 text-xs" style="text-wrap: wrap; text-align: justify;">
                        <p id="blog_preview"><a href="view_blog.php?blog_id=${blog.blog_id}">${blog.content}</a></p>
                      </td>
                      <td class="px-4 py-3 text-sm" id="blog_published">
                        Great Performance
                      </td>
                      <td class="px-4 py-3 text-sm" id="blog_published">
                        ${blog.created_at.slice(0, 10)}
                      </td>
                    </tr>`
        blog_viewer.insertAdjacentHTML("beforeend", blog_view);

        
    });
})
.catch((err) => {
    console.log(err)
})



