const blog_title = document.querySelector("#blog_title");
const image = document.getElementById("featured");
const blog_body = document.querySelector("#blog_editor");
const form = document.querySelector("form");
const tags = document.querySelector("#tags");
const categories = document.querySelector("#categories");
// const renderer = document.querySelector('#render')
let imageBase64;

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
  const blog_content = {
    title: `${blog_title.value}`,
    image: `${imageBase64}`,
    content: `${blog_body.innerText}`,
    tags: `${tags.value}`,
    categories: `${categories.value}`,
  };

  const configData = {
    method: "POST",
    mode: "no-cors",
    headers: {
      "Conten-Type": "application/json",
      "Accept": "application/json",
    },
    body: JSON.stringify(blog_content),
  };

  //   console.log(blog_content);
  fetch(
    "http://blogs.skaetch.com/blog_services/blogpoint/blog/createone",
    configData
  )
  .then(async (response) => {
      // Check if the response is not OK, then read as text
      if (!response.ok) {
        const text = await response.text();
        console.log(text + " response not ok status 22");
        // alert(text, " response not ok status 22");
        location.href ="../index.html"
        // throw new Error(Error `response from server:${text}`);
      }
      return response.json();
    })
    .then(data => {
        console.log(data);
    })
    // .then((res) => res.json())
    // .then((data) => {
    //   console.log(data);
    // })
    // .catch((err) => {
    //   console.log(err);
    // });
});
