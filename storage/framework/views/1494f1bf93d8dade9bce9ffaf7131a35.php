<?php $__env->startSection('content'); ?>
    <div class="container">
        <?php if(!empty($todayMatches)): ?>
            <?php
                $currentTournament = '';
                $tournamentIndex = 0;
            ?>
            <div class="accordion" id="accordionExample">
                <?php $__currentLoopData = $todayMatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($currentTournament !== $match['Name']): ?>
                        <?php if($currentTournament !== ''): ?>
            </div>
    </div>
    <?php endif; ?>
    <?php
        $currentTournament = $match['Name'];
        $tournamentIndex++;
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?php echo e($tournamentIndex); ?>">
            <button class="accordion-button" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($tournamentIndex); ?>"
                    aria-expanded="true"
                    aria-controls="collapse<?php echo e($tournamentIndex); ?>">
                <?php echo e($currentTournament); ?>

            </button>
        </h2>
        <div id="collapse<?php echo e($tournamentIndex); ?>"
             class="accordion-collapse collapse show"
             aria-labelledby="heading<?php echo e($tournamentIndex); ?>" data-bs-parent="#accordion<?php echo e($tournamentIndex); ?>">
            <div class="accordion-body">
                <?php endif; ?>
                <div class="match transition-fast">
                    <div class="status"><?php echo e($match['status']); ?></div>
                    <div class="datetime"><?php echo e(date('G:i', strtotime($match['DateTime UTC']))); ?></div>
                    <div class="teams">
                        <div class="team1">
                            <img src="<?php echo e($match['Team1Image']); ?>" alt="<?php echo e($match['Team1']); ?>"/>
                            <?php echo e($match['Team1']); ?>

                        </div>
                        <div class="team2">
                            <img src="<?php echo e($match['Team2Image']); ?>" alt="<?php echo e($match['Team2']); ?>"/>
                            <?php echo e($match['Team2']); ?>

                        </div>
                    </div>
                    <div class="result">
                        <div class="result1"><?php echo e($match['Team1Score'] ?? '-'); ?></div>
                        <div class="result2"><?php echo e($match['Team2Score'] ?? '-'); ?></div>
                    </div>
                    <div class="stream">
                        <?php if(!empty($match['Stream'])): ?>
                            <a href="<?php echo e($match['Stream']); ?>" target="_blank">
                                <img src="<?php echo e(asset('img/tv_icon.png')); ?>" alt="Stream Link">
                            </a>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div> <!-- Close the last tournament's accordion body -->
        </div> <!-- Close the last accordion item -->
        <?php else: ?>
            <p>No data available.</p>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/GEsports/resources/views/pages/home.blade.php ENDPATH**/ ?>