import smtplib, sys
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

# python3 backend/static-files/python/smtp.py [destination address] [verification code]

smtp_url = 'smtp.gmail.com'
sender = 'wsuMentorshipVerify@gmail.com'
app_specific_password = 'kwsgzcyxynvuetvr'
recver = sys.argv[1]

msg = MIMEMultipart('alternative')
msg['Subject'] = 'verify'
text = 'Your verification code is: ' + sys.argv[2]


def main():
	with smtplib.SMTP(smtp_url, 587) as smtp_server:
		smtp_server.starttls()
		smtp_server.login(sender, app_specific_password)

		msg['From'] = 'wsuMentorshipVerify@gmail.com'
		part1 = MIMEText(text, 'plain')
		msg.attach(part1)
		
		smtp_server.sendmail(sender, recver, msg.as_string())


if __name__ == '__main__':
	main()
