import smtplib, sys

sender_email_addr = 'eimyatnoeaung98@gmail.com'

# The corresponding password for sender_email_addr
# How to generate an application specific password: support.google.com/accounts/answer/185833?hl
# This is Google's way of allowing "unsecure devices" to access your Gmail account.
app_specific_password = 'nabubrqsybtvppic'


recver_email_addr = sys.argv[1]
verification_code = sys.argv[2]
print(recver_email_addr)
# recver_email_addr = 'eimyatnoeaung98@gmail.com'

email_body_content = '\n\nEnter this verification code: ' + verification_code + '\n\n'

def main():
	# Standard SMTP port
	with smtplib.SMTP('smtp.gmail.com', 587) as smtp_server:
		# Start the Transport Layer Security (which then requires us to sign in, although SMTP can be easily spoofed)
		smtp_server.starttls()

		# Log in with your email address and aplication specific password
		smtp_server.login(sender_email_addr, app_specific_password)

		# Perform's an entire mail transaction
		smtp_server.sendmail(sender_email_addr, recver_email_addr, email_body_content)

if __name__ == '__main__':
	try:
		print('Destination address:', recver_email_addr)
		main()
		print('Email sent successfully.')
	except Exception:
		print('Exception occured while sending email.')

