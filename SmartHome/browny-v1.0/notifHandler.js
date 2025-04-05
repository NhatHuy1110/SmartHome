
$(document).ready(function () {

    $('.notification').load('Notification.php');
    $('.counter').text('0').hide();

    var counter = 0;
    let prevnotifnum = localStorage.getItem('prevnotifnum') || 0;

    function loadNotifications() {
        $.get('Notification.php', function (data) {
            if (data) {
                let notificationData = JSON.parse(data);
                if (notificationData.length > 0 && notificationData.length != prevnotifnum) {
                    if (notificationData.length > prevnotifnum) {
                        counter += notificationData.length - prevnotifnum;
                    } else counter = notificationData.length
                    prevnotifnum = notificationData.length // do not remove this
                    localStorage.setItem('prevnotifnum', notificationData.length);
                    $('.counter').text(counter).show(); // Reveal red number if new notification not 0
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
        $('.counter').text('0').hide(); // Reset counter when clicked
    });
});