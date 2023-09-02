const headerInfo = document.createElement('template');

headerInfo.innerHTML = `
    <title>WSU Mentorship Program</title>

    <meta charset="UTF-8">
    <meta name="author" content="Matthew Jilk and the gang">
    <meta name="keywords" content="WSU CoSE Mentorship Program"> 
    <meta name="description" content="matches mentors and mentees in the Winona State University">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="styles/common.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
`;

document.head.appendChild(headerInfo.content);