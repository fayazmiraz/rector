<?php

namespace Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeReturnMixedTypeDeclarationRector\Fixture;

class SomeClass
{
    public function getAnything(): mixed
    {
        if (mt_rand()) {
            return 1;
        }

        return 'value';
    }
}

?>
-----
<?php

namespace Rector\DowngradePhp80\Tests\Rector\FunctionLike\DowngradeReturnMixedTypeDeclarationRector\Fixture;

class SomeClass
{
    /**
     * @return mixed
     */
    public function getAnything()
    {
        if (mt_rand()) {
            return 1;
        }

        return 'value';
    }
}

?>
