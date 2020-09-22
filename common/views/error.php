<?php
$this->title = $name;
?>
<div class="container mt-5">
    <main class="empty-state empty-state-fullpage bg-primary">
        <!-- .empty-state-container -->
        <div class="empty-state-container">
            <section class="card">
                <header class="card-header bg-light text-left">
                    <i class="fa fa-fw fa-circle text-red"></i>
                    <i class="fa fa-fw fa-circle text-yellow"></i>
                    <i class="fa fa-fw fa-circle text-teal"></i>
                </header>
                <div class="card-body">
                    <h1 class="state-header display-1 font-weight-bold">
                        <span>4</span>
                        <i class="far fa-frown text-red"></i>
                        <span>4</span>
                    </h1>
                    <h2><?php echo $message; ?></h2>
                    <p class="state-description lead">Xin lỗi, chúng tôi đã đặt sai địa chỉ URL đó hoặc nó trỏ đến một thứ không tồn tại.</p>
                </div>
            </section>
        </div>
        <!-- /.empty-state-container -->
    </main>
</div>