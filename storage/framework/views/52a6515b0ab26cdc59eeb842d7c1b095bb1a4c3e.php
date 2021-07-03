<article class="card" data-id="<?php echo e($card->id); ?>">
<header>
  <h2><a href="/cards/<?php echo e($card->id); ?>"><?php echo e($card->name); ?></a></h2>
  <a href="#" class="delete">&#10761;</a>
</header>
<ul>
  <?php echo $__env->renderEach('partials.item', $card->items()->orderBy('id')->get(), 'item'); ?>
</ul>
<form class="new_item">
  <input type="text" name="description" placeholder="new item">
</form>
</article>
<?php /**PATH /home/luis/git/A4S2/LBAW/attempt4/template-laravel/template-laravel/resources/views/partials/card.blade.php ENDPATH**/ ?>