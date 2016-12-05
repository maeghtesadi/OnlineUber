ASSUMPTIONS:

Homepage:
The homepage is made public for everyone to see.
Public messages and statistics are displayed on homepage.

Users:
Users must login to use Super.
A new user can only register if his email is in the 'invitations' table.
Emails are usernames and are unique.
CreditCard numbers 'ccnum' are unique.
Suspended members cannot login, inactive members can login, but cannot use website functionnalities.
Once a new member registers, his status is inactive, the admin must manually activate user before he can use Super.
A member can deposit funds in edit in account Info(editinfo.php).
At registration, license info and insurance info is optional, if not entered, user cannot post trips.

Trips:
Special trips take the form of a special text description the driver can add to his trip.
Once a trip is created, members excluding user who posted can see the trip and its info, book the trip and message the driver, after which the driver can choose to confirm or ignore the rider's request, once it the member is confirmed, the trips is assumed to take place and finally when the trip is rated, it is assumed completed.
A user with insufficient funds for a trip cannot book it.
Every posted and deleted trips will appear in the admin page (tableHistory), this was implemented using triggers on inserti and delete queries from trips table.
A user can pay for his completed trips on Make Payment(bills.php)
A driver can edit his trip or cancel in Edit Trip.

Messaging:
Users related to a same trip, driver-rider or rider-driver can message each other.
A user can message any other user by entering his email.
Messages take form of a live-chat system.



