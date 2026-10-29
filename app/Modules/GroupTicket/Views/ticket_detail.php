<div class="modal-header">
    <h5 class="modal-title" id="staticBackdropLabel">Group Ticket Detail</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <table class="table table-bordered">
        <tbody>
            <?php foreach ($keyArray as $key => $value) :
                if (isset($detail[$key]) && !empty($detail[$key])) :
            ?>
                    <tr>
                        <th scope="row"><?= $value ?></th>
                        <td><?= $detail[$key] ?></td>
                    </tr>
            <?php endif;
            endforeach; ?>
            <tr>
                <th scope="row">Created</th>
                <td><?= date_created_format($detail['created']) ?></td>
            </tr>
        </tbody>
    </table>
</div>