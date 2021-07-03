<li class="item" data-id="<?php echo e($item->id); ?>">
  <label>
    <input type="checkbox" <?php echo e($item->done?'checked':''); ?>>
    <span><?php echo e($item->description); ?></span>
    <a href="#" class="delete">&#10761;</a>
  </label>
</li>
<?php /**PATH /home/luis/git/A4S2/LBAW/attempt4/template-laravel/template-laravel/resources/views/partials/item.blade.php ENDPATH**/ ?>