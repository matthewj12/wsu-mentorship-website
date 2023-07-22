const headerInfo = document.createElement('template');

headerInfo.innerHTML = `
    <title>WSU Mentorship Program</title>

    <meta charset="UTF-8">
    <meta name="author" content="Matthew Jilk and the gang">
    <meta name="keywords" content="WSU CoSE Mentorship Program"> 
    <meta name="description" content="matches mentors and mentees in the Winona State University">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="styles/common.css">
`;

document.head.appendChild(headerInfo.content);