<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="flex gap-2">
        <input type="search"
               class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary"
               placeholder="搜索..."
               value="<?php echo get_search_query(); ?>"
               name="s"
               required>
        <button type="submit"
                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition-colors">
            搜索
        </button>
    </div>
</form>
