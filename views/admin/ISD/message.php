<a href="#" style="text-decoration: none; float: right;">
    <div style="position: relative; display: inline-block; margin-right: 25px;">
            <span id="message-count"
                style="position: absolute; top: -10px; right: -10px;
                        display: none;
                        background: red; color: white;
                        border-radius: 50%;
                        width: 20px; height: 20px;
                        font-size: 12px;
                        text-align: center; line-height: 20px;">
            </span>
            <img width="25px" 
                src="../../../public/images/viewComment.svg"
                alt="Activities need attention" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#offcanvasWithBothOptions"
                aria-controls="offcanvasWithBothOptions"
                data-contract-id="<?= $getContract['id'] ?>"
                data-audit-id="<?= $user_id ?>"
                data-user-id="<?= $user_id ?>"
                data-department="<?= $user_department ?>"
                class="view-comment-trigger">
    </div>
</a>

<script>
    function fetchNotificationCount() {
        fetch('contracts/message_notification_count.php') // this is your PHP endpoint
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('message-count');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                } 
                
            })
            .catch(error => {
                console.error('Error fetching notification count:', error);
            });
    }

    // Fetch on page load
    fetchNotificationCount();

    // Repeat every 10 seconds
    setInterval(fetchNotificationCount, 10000);
</script>

