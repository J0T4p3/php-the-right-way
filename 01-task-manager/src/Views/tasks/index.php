<?php ob_start(); ?>
<div class="card mx-5">
    <form method="post" action="/create" class="card-body">
        <div class="mb-3">
            <label for="task" class="form-label">Tarefa</label>
            <input type="text" class="form-control" name="newTask">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<div class="row mt-4 mx-5 g-3">
    <?php if (!$tasks) : ?>
        <div class="text-center mt-5">Nenhuma tarefa cadastrada</div>
    <?php else: ?>
        <?php foreach ($tasks as $id => $task): ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="row card-body">
                        <div class="col">
                            <h5><?php echo $id . " - " . htmlspecialchars($task["name"]) ?></h5>
                            <p>Status: <?php echo $task["completed"] ? 'Concluído' : 'Não Concluído' ?></p>
                        </div>
                        <div class="col-2 d-flex align-items-center">
                            <form method="post" action="/toggle">
                                <input type="hidden" name="toggTask" value="<?php echo $id ?>">
                                <input onchange="this.form.submit()" class="form-check-input" type="checkbox" <?php echo $task["completed"] ? 'checked' : '' ?>>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer d-flex">
                        <form method="post" action="/delete">
                            <input type="hidden" name="delTask" value="<?php echo $id ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean();
require __DIR__ . '/../layout.php'; ?>
