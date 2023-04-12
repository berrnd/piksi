<?php

namespace Piksi\Services;

class PictureService extends BaseService
{
	public function GetRootFolderInfo()
	{
		$folders = [];

		for ($i = 0; $i < count(PIKSI_FOLDERS); $i++)
		{
			$folder = PIKSI_FOLDERS[$i];
			$folderPath = rtrim($folder['path'], '/');

			if (is_dir($folderPath))
			{
				$subFolderInfo = $this->GetFolder($i, '/', true);
				$foldersCount = 0;
				$picturesCount = 0;
				$videosCount = 0;
				foreach ($subFolderInfo as $info)
				{
					if ($info['type'] == 'picture')
					{
						$picturesCount++;
					}
					elseif ($info['type'] == 'video')
					{
						$videosCount++;
					}
					elseif ($info['type'] == 'folder')
					{
						$foldersCount++;
					}
				}

				$folders[] = [
					'name' => $folder['name'],
					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount
				];
			}
		}

		return $folders;
	}

	public function GetFolder(int $folderIndex, string $subFolderPathRelative, bool $skipSubfolders = false)
	{
		$subFolderPathRelative = rtrim($subFolderPathRelative, '/');

		if (!array_key_exists($folderIndex, PIKSI_FOLDERS))
		{
			throw new \Exception('Invalid folder index ' . $folderIndex);
		}

		$folderPath = realpath(PIKSI_FOLDERS[$folderIndex]['path']);
		$subFolderPath = realpath($folderPath . '/' . ltrim($subFolderPathRelative, '/'));

		if (!string_starts_with($subFolderPath, $folderPath))
		{
			throw new \Exception($subFolderPathRelative . ' is not a subfolder of ' . $folderPath . ' or it doesn\'t exist');
		}

		$items = [];
		foreach (new \DirectoryIterator($subFolderPath) as $file)
		{
			if ($file->isDot())
			{
				continue;
			}

			$fileExtension = strtolower($file->getExtension());

			if ($file->isDir())
			{
				if ($file->getFilename() == PIKSI_THUMBS_FOLDER_NAME)
				{
					continue;
				}

				$coverImagePathRelative = $subFolderPathRelative . '/' . $file->getFilename() . '/' . PIKSI_ALBUM_COVER_FILENAME;
				if (!file_exists($file->getRealPath() . '/' . PIKSI_ALBUM_COVER_FILENAME))
				{
					$coverImagePathRelative = '';
				}

				$foldersCount = 0;
				$picturesCount = 0;
				$videosCount = 0;
				if (!$skipSubfolders)
				{
					$subFolderInfo = $this->GetFolder($folderIndex, $subFolderPathRelative . '/' . $file->getFilename());
					foreach ($subFolderInfo as $info)
					{
						if ($info['type'] == 'picture')
						{
							$picturesCount++;
						}
						elseif ($info['type'] == 'video')
						{
							$videosCount++;
						}
						elseif ($info['type'] == 'folder')
						{
							$foldersCount++;
						}
					}
				}

				$items[] = [
					'type' => 'folder',
					'sort_name' => '_a_' . $file->getFilename(),
					'name' => $file->getFilename(),
					'relativePath' => $subFolderPathRelative . '/' . $file->getFilename(),
					'coverImagePathRelative' => $coverImagePathRelative,
					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount,
				];
			}
			elseif ($file->isFile() && in_array($fileExtension, array_merge(PIKSI_PICTURE_FILEEXT, PIKSI_VIDEO_FILEEXT)))
			{
				if ($file->getFilename() == PIKSI_ALBUM_COVER_FILENAME)
				{
					continue;
				}

				$thumbRelativePath = $subFolderPathRelative . '/' . PIKSI_THUMBS_FOLDER_NAME . '/' . $file->getFilename();
				if (!file_exists(str_replace($file->getFilename(), PIKSI_THUMBS_FOLDER_NAME . '/' . $file->getFilename(), $file->getRealPath())))
				{
					$thumbRelativePath = $subFolderPathRelative . '/' . $file->getFilename();
				}

				$type = 'picture';
				if (in_array($fileExtension, PIKSI_VIDEO_FILEEXT))
				{
					$type = 'video';
				}

				$items[] = [
					'type' => $type,
					'sort_name' => '_b_' . $file->getFilename(),
					'relativePath' => $subFolderPathRelative . '/' . $file->getFilename(),
					'thumbRelativePath' => $thumbRelativePath
				];
			}
		}

		$sort = SORT_ASC;
		if (array_key_exists('sort', PIKSI_FOLDERS[$folderIndex]) && PIKSI_FOLDERS[$folderIndex]['sort'] == 'desc')
		{
			$sort = SORT_DESC;
		}

		array_multisort(array_column($items, 'sort_name'), $sort, $items);

		return $items;
	}
}
