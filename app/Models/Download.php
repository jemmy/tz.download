<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Download
 * @package App\Models
 *
 * @property int $id
 * @property int $status
 * @property string $url
 */
class Download extends Model
{
    public const DOWNLOAD_STATUS_PENDING = 1;
    public const DOWNLOAD_STATUS_DOWNLOADING = 2;
    public const DOWNLOAD_STATUS_COMPLETE = 3;
    public const DOWNLOAD_STATUS_ERROR = 4;

    protected $fillable = ['status', 'url'];

    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();
    }

    public function getStatusName(): string
    {
        switch ($this->status) {
            case self::DOWNLOAD_STATUS_PENDING:
                return 'Pending';
            case self::DOWNLOAD_STATUS_DOWNLOADING:
                return 'Downloading';
            case self::DOWNLOAD_STATUS_COMPLETE:
                return 'Complete';
            case self::DOWNLOAD_STATUS_ERROR:
                return 'Error';
            default:
                return 'Unknown';
        }
    }
}
