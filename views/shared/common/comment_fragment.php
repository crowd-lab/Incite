
                <div id="container" style="padding-left: 15px;">
                    <h3> Comment </h3>

                    <div id="onLogin">
                    <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted * */): ?>
                                <textarea name="transcribe_text" cols="60" rows="10" id="comment" class="comment-textarea" placeholder="Your comment"></textarea>
                                <button type="button" class="btn btn-default submit-comment-btn" onclick="submitComment(<?php echo $this->tag->id; ?>)">Post Comment</button>
                    <?php else: ?>
                        Please login or signup to join the discussion!
                    <?php endif; ?>
                    </div>
                    <br>
                    <br>
                    <ul id="comments" class="comments-list"></ul>
                </div>
