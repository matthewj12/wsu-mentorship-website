const template = document.createElement('template');

template.innerHTML = `
    <title>WSU CoSE Mentorship</title>

    <meta charset="UTF-8">
    <meta name="author" content="Ei Myatnoe Aung, Matthew Jilk">
    <meta name="keywords" content="WSU CoSE Mentorship Program"> 
    <meta name="description" content="Matches Mentors and Mentees in the College of Science and Engineering at WSU">
    <!-- <meta http-equiv="refresh" content="30"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="common.css">

    <script src="index.js"></script>
`;

document.head.appendChild(template.content);
