<?php namespace Modules\Workshop\Scaffold\Theme;

use Illuminate\Filesystem\Filesystem;
use Modules\Workshop\Scaffold\Theme\Exceptions\ThemeExistsException;
use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ThemeScaffold
{
    use FindsThemePath;

    /**
     * @var array
     */
    protected $files = [
        'themeJson',
        'gulpfileJs',
        'packageJson',
        'baseLayout',
    ];
    /**
     * Options array containing:
     *  - name
     *  - type
     * @var array
     */
    protected $options;

    /**
     * @var ThemeGeneratorFactory
     */
    private $themeGeneratorFactory;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;

    public function __construct(ThemeGeneratorFactory $themeGeneratorFactory, Filesystem $finder)
    {
        $this->themeGeneratorFactory = $themeGeneratorFactory;
        $this->finder = $finder;
    }

    /**
     * @throws Exceptions\FileTypeNotFoundException
     * @throws ThemeExistsException
     */
    public function generate()
    {
        if ($this->finder->isDirectory($this->themePath($this->options['name']))) {
            throw new ThemeExistsException();
        }

        $this->finder->makeDirectory($this->themePath($this->options['name']));

        foreach ($this->files as $file) {
            $this->themeGeneratorFactory->make($file, $this->options)->generate();
        }
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->options['name'] = $name;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function forType($type)
    {
        $this->options['type'] = $type;

        return $this;
    }

    /**
     * Set the files array on the class
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }
}
