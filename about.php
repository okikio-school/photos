<head>
  <title>About</title>


  <?php include_once "partials/meta.php"; ?>
  <link rel="stylesheet" type="text/css" href="styles/about.css">
  <link rel="stylesheet" href="about.css">
</head>

<body>
  <?php include_once "partials/navbar.php"; ?>

  <main id="description">
    <div class="container">

      <h1 class="font-serif">About Our Project</h1>
      <p> By: Okiki Ojo, Steven Mai, Kramptj KC, Tazrian Tarfee </p>
      <!-- <br>   -->
      <!-- <hr /> -->
      <br>  
      <br>  

      <h3 class="font-700 font-serif"> What is it? </h3>
      <div class="first_p">
        <p> We are making an Art Gallery System that allows for multiple users to upload images to Cloudinary (an online
          image storage provider) and uses SQL databases to store the metadata about the images as well as a separate
          one to store user details. We will use SQL & PHP for the image filter system, with CSS, and Javascript being
          used for the design of the site, as well as handling the user image upload. We decided to store images in
          Cloudinary because we did not think SQL would be able to scale properly if we started using it to store
          images, so, we decided to just upload all the images to Cloudinary, and have a local database containing the
          image url, the tags for the image, user accounts, likes/dislikes, user profiles, and friends list.</p>
      </div>
      <br>  
      <br>  

      <h3 class="font-700 font-serif"> The Elements </h3>

      <div class="second_p">
        <p> For the elements of the website, it will be divided up between four people. Kramptj and Tazrian will work on
          the front-end elements of the websites with the html, css and javascript elements, this includes handling the
          front-end aspect of the friends list, user account sign in, user profile pages, generating and editing image
          tags, etc. Okiki and Steven will work on the Cloudinary aspects as well as the image upload aspects, reliable
          and scalable image uploads and the up to date local database containing the url of the image. All four of us
          will work on the SQL & Php elements, it will handle the filtering system as well as the tagging, user
          accounts, likes/dislikes, user profiles, and friend list on the backend database side. </p>
      </div>
      <br>  
      <br>  

      <h3 class="font-700 font-serif"> The Puropose? </h3>

      <div class="third_p">
        <p> We are making this project, so users can self host their images without having to rely on big tech
          companies. Cloudinary has a 25GB free plan for image storage, which we can take advantage of, reducing the
          cost for storing images, we can then set up a localhost WAMP server for hosting our Php. The project is the
          culmination of Google Photos, Unsplash, and Pinterest all backed into one site. Our aim is to use all we have
          learned in the Web Programming course, to create a project that actual people can use to lessen their
          dependence on Google, Apple, Microsoft, etc.</p>
      </div>
      <br>  
      <br>  

      <h3 class="font-700 font-serif"> How is it Put Together? </h3>

      <div class="fourth_p">
        <p> To complete this project, we will need Git & GitHub, SQL, Cloudinary, Php, Sass, CSS, Tailwind,
          Solidjs/React (we are not sure which technology works best for this project), Typescript and lots of time &
          patience. We will develop this project with an Open Source MIT license allowing other devs to contribute, and
          other users to self host their own. By the time the project is complete we should have a fully functioning
          frontend site, an efficient and effective backend SQL & Php server, and a scalable Cloudinary image storage
          setup. All aspects of the project are going to be done in parallel with each other. We are expecting to do our
          presentation by December 2. So, we need to ensure the Cloudinary image storage is ready by November 24 at the
          latest, with the Php and Sql server next by November 28, and last but not least the frontend ready by November
          30. </p>
      </div>
    </div>
  </main>
</body>

</html>