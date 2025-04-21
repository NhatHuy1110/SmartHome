
$(document).ready(function () {

    $('.notification').load('Notification.php');

    let counter = parseInt(localStorage.getItem('counter')) || 0;
    let prevNotifNum = parseInt(localStorage.getItem('prevNotifNum')) || 0;

    if (counter > 0) {
        $('.counter').text(counter).show(); // Show red number if new notification
    } else {
        $('.counter').text('0').hide(); // Hide if not
    }

    function loadNotifications() {
        $.get('Notification.php', function (data) {
            if (data) {
                let notificationData = JSON.parse(data);
                if (notificationData.length > 0 && notificationData.length != prevNotifNum) {
                    if (notificationData.length > prevNotifNum) {
                        counter += notificationData.length - prevNotifNum;
                    } else {
                        counter = notificationData.length
                    }

                    prevNotifNum = notificationData.length // do not remove this

                    localStorage.setItem('counter', counter); // Update localStorage
                    localStorage.setItem('prevNotifNum', prevNotifNum); // Update prevNotifNum in localStorage

                    if (counter > 0) {
                        $('.counter').text(counter).show(); // Show red number if new notification
                    } else {
                        $('.counter').text('0').hide(); // Hide if not
                    }
                }
                // dropdown list
                $('.notification').html('');
                notificationData.forEach(function (item) {
                    $('.notification').append(`
                            <div class="dropdown-item">
                                <h6>${item.Error_Message}</h6>
                                <span>${item.DateTime}</span>
                                <hr class="mt-1 mb-1">
                            </div>
                        `);
                });
            }
        });
    }

    loadNotifications();

    setInterval(loadNotifications, 3000);

    $('#numUnseen').on('click', function () {
        counter = 0;
        localStorage.setItem('counter', 0);
        $('.counter').text('0').hide(); // Reset counter when clicked
    });
});