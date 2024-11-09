<style>
    /* Modal CSS */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    justify-content: center;
    align-items: center;
    z-index: 50;
}

.modal.show {
    display: flex;
}

.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
}

</style>

<!-- Track Scores Modal -->
<div id="trackScoresModal" class="modal">
    <div class="modal-content">
        <button onclick="closeModal('trackScoresModal')" class="button-secondary">Close</button>
    </div>
</div>

<!-- Track Progress Modal -->
<div id="trackProgressModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Track Progress</div>
        <p>This modal allows you to track your game progress.</p>
        <button onclick="closeModal('trackProgressModal')" class="button-secondary">Close</button>
    </div>
</div>

<!-- Leaderboard Modal -->
<div id="leaderboardModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Leaderboard</div>
        <p>This modal shows the ranking of participants.</p>
        <button onclick="closeModal('leaderboardModal')" class="button-secondary">Close</button>
    </div>
</div>

<!-- Rewards Modal -->
<div id="rewardsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Rewards</div>
        <p>This modal shows available rewards for the game.</p>
        <button onclick="closeModal('rewardsModal')" class="button-secondary">Close</button>
    </div>
</div>
<script>
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
        } else {
            console.error('Modal with ID ' + modalId + ' not found.');
        }
    }
</script>