.PHONY: test static coverage profile-clear

test:
	vendor/bin/phpunit --color

static:
	psalm --show-info=true
	

coverage:
	XDEBUG_MODE=coverage vendor/bin/phpunit \
		--coverage-clover $(PHPUNIT_COVERAGE_PATH)/cov.xml \
		--coverage-filter src \
		--coverage-html $(PHPUNIT_COVERAGE_PATH);
	hash xdg-open && xdg-open $(PHPUNIT_COVERAGE_PATH)/index.html;

profile-clear:
	rm $(XDEBUG_OUTPUT_DIR)/cachegrind.out.*
