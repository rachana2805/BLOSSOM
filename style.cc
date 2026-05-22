/* COMMON STYLING */

body{
margin:0;
font-family:'Poppins',sans-serif;
}

/* NAVBAR */

nav{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 80px;
background:white;
box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.logo{
font-family:'Playfair Display',serif;
font-size:28px;
color:#b03a5b;
}

nav ul{
list-style:none;
display:flex;
gap:30px;
}

nav ul li a{
text-decoration:none;
color:black;
font-weight:500;
}

nav ul li a:hover{
color:#b03a5b;
}

/* CONTACT SECTION */

.contact-section{
padding:120px 20px;
background:linear-gradient(to bottom,#fff,#f6eef1);
display:flex;
justify-content:center;
}

.contact-box{
background:white;
width:100%;
max-width:600px;
padding:60px 50px;
border-radius:30px;
text-align:center;
box-shadow:0 20px 50px rgba(0,0,0,0.08);
}

.contact-box h2{
font-family:'Playfair Display',serif;
font-size:34px;
color:#8b1e3f;
margin-bottom:10px;
}

.subtitle{
color:#888;
margin-bottom:40px;
font-style:italic;
}

.contact-item{
display:flex;
align-items:center;
justify-content:center;
gap:15px;
margin:15px 0;
font-size:16px;
color:#444;
}

.contact-item i{
color:#c75b6d;
}

.social-icons{
margin-top:35px;
display:flex;
justify-content:center;
gap:20px;
}

.icon{
width:50px;
height:50px;
display:flex;
align-items:center;
justify-content:center;
border-radius:50%;
color:white;
font-size:20px;
transition:0.3s ease;
}

.instagram{
background:linear-gradient(45deg,#f58529,#dd2a7b,#8134af,#515bd4);
}

.whatsapp{
background:#25D366;
}

.icon:hover{
transform:scale(1.15);
box-shadow:0 10px 25px rgba(0,0,0,0.15);
}